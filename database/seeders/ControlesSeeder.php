<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ControlesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpiar datos existentes (opcional, comentar si no quieres borrar datos)
        // DB::table('controles_menor1')->truncate();
        // DB::table('controles_rn')->truncate();
        // DB::table('tamizaje_neonatal')->truncate();
        // DB::table('vacuna_rn')->truncate();
        // DB::table('visitas_domiciliarias')->truncate();
        // DB::table('datos_extra')->truncate();
        // DB::table('recien_nacido')->truncate();

        // Obtener todos los niños de la base de datos
        $ninos = DB::table('niños')->get();

        if ($ninos->isEmpty()) {
            $this->command->warn('⚠️  No hay niños en la base de datos. Por favor, crea algunos niños primero.');
            return;
        }

        $this->command->info('🔄 Iniciando importación de controles para ' . $ninos->count() . ' niños...');

        foreach ($ninos as $nino) {
            $ninoId = $nino->id_niño;
            $fechaNacimiento = Carbon::parse($nino->fecha_nacimiento);
            $hoy = Carbon::now();
            $edadDias = $fechaNacimiento->diffInDays($hoy);

            $this->command->info("📋 Procesando niño ID: {$ninoId} - {$nino->apellidos_nombres} (Edad: {$edadDias} días)");

            // 1. DATOS DEL RECIÉN NACIDO (si tiene menos de 29 días)
            if ($edadDias <= 28) {
                $this->crearRecienNacido($ninoId);
            }

            // 2. CONTROLES RECIÉN NACIDO (CRN 1-4) - para niños de 0-28 días
            if ($edadDias <= 28) {
                $this->crearControlesRN($ninoId, $fechaNacimiento, $edadDias);
            }

            // 3. CONTROLES CRED MENSUAL (1-11) - para niños de 29-359 días
            if ($edadDias >= 29 && $edadDias <= 359) {
                $this->crearControlesCredMensual($ninoId, $fechaNacimiento, $edadDias);
            }

            // 4. TAMIZAJE NEONATAL - para niños de 1-29 días
            if ($edadDias >= 1 && $edadDias <= 29) {
                $this->crearTamizaje($ninoId, $fechaNacimiento);
            }

            // 5. VACUNAS RECIÉN NACIDO - para niños de 0-30 días
            if ($edadDias <= 30) {
                $this->crearVacunasRN($ninoId, $fechaNacimiento);
            }

            // 6. VISITAS DOMICILIARIAS - para todos los niños menores de 1 año
            if ($edadDias <= 365) {
                $this->crearVisitasDomiciliarias($ninoId, $fechaNacimiento, $edadDias);
            }

            // 7. DATOS EXTRA - para todos los niños
            $this->crearDatosExtra($ninoId, $nino);
        }

        $this->command->info('✅ Importación de controles completada exitosamente!');
    }

    /**
     * Crear registro de recién nacido
     */
    private function crearRecienNacido($ninoId)
    {
        $existe = DB::table('recien_nacido')->where('id_niño', $ninoId)->exists();
        if ($existe) {
            $this->command->line("  ⏭️  Recién nacido ya existe para niño {$ninoId}");
            return;
        }

        // Determinar clasificación basada en peso y edad gestacional
        $peso = rand(2500, 4000) / 100; // 2.5 a 4.0 kg
        $edadGestacional = rand(37, 42); // semanas
        
        // Clasificar: Normal o Bajo Peso al Nacer y/o Prematuro
        // Bajo peso: < 2.5 kg, Prematuro: < 37 semanas
        if ($peso < 2.5 || $edadGestacional < 37) {
            $clasificacion = 'Bajo Peso al Nacer y/o Prematuro';
        } else {
            $clasificacion = 'Normal';
        }
        
        DB::table('recien_nacido')->insert([
            'id_niño' => $ninoId,
            'peso' => $peso,
            'edad_gestacional' => $edadGestacional,
            'clasificacion' => $clasificacion,
        ]);

        $this->command->line("  ✅ Recién nacido creado para niño {$ninoId}");
    }

    /**
     * Crear controles de recién nacido (CRN 1-4)
     */
    private function crearControlesRN($ninoId, $fechaNacimiento, $edadDias)
    {
        $rangos = [
            1 => ['min' => 2, 'max' => 6],
            2 => ['min' => 7, 'max' => 13],
            3 => ['min' => 14, 'max' => 20],
            4 => ['min' => 21, 'max' => 28],
        ];

        foreach ($rangos as $numeroControl => $rango) {
            // Solo crear controles que corresponden a la edad actual
            if ($edadDias >= $rango['min']) {
                $existe = DB::table('controles_rn')
                    ->where('id_niño', $ninoId)
                    ->where('numero_control', $numeroControl)
                    ->exists();

                if (!$existe) {
                    $fechaControl = $fechaNacimiento->copy()->addDays(rand($rango['min'], min($rango['max'], $edadDias)));
                    $edadControl = $fechaNacimiento->diffInDays($fechaControl);

                    DB::table('controles_rn')->insert([
                        'id_niño' => $ninoId,
                        'numero_control' => $numeroControl,
                        'fecha' => $fechaControl->format('Y-m-d'),
                        'edad' => $edadControl,
                        'estado' => ['Completo', 'Pendiente', 'Atrasado'][rand(0, 2)],
                    ]);

                    $this->command->line("  ✅ Control RN {$numeroControl} creado");
                }
            }
        }
    }

    /**
     * Crear controles CRED mensual (1-11)
     */
    private function crearControlesCredMensual($ninoId, $fechaNacimiento, $edadDias)
    {
        $rangos = [
            1 => ['min' => 29, 'max' => 59],
            2 => ['min' => 60, 'max' => 89],
            3 => ['min' => 90, 'max' => 119],
            4 => ['min' => 120, 'max' => 149],
            5 => ['min' => 150, 'max' => 179],
            6 => ['min' => 180, 'max' => 209],
            7 => ['min' => 210, 'max' => 239],
            8 => ['min' => 240, 'max' => 269],
            9 => ['min' => 270, 'max' => 299],
            10 => ['min' => 300, 'max' => 329],
            11 => ['min' => 330, 'max' => 359],
        ];

        foreach ($rangos as $numeroControl => $rango) {
            // Solo crear controles que corresponden a la edad actual
            if ($edadDias >= $rango['min']) {
                $existe = DB::table('controles_menor1')
                    ->where('id_niño', $ninoId)
                    ->where('numero_control', $numeroControl)
                    ->exists();

                if (!$existe) {
                    $fechaControl = $fechaNacimiento->copy()->addDays(rand($rango['min'], min($rango['max'], $edadDias)));
                    $edadControl = $fechaNacimiento->diffInDays($fechaControl);

                    DB::table('controles_menor1')->insert([
                        'id_niño' => $ninoId,
                        'numero_control' => $numeroControl,
                        'fecha' => $fechaControl->format('Y-m-d'),
                        'edad' => $edadControl,
                        'estado' => ['Completo', 'Pendiente'][rand(0, 1)],
                        'estado_cred_once' => ['Adecuado', 'Riesgo', 'Retraso'][rand(0, 2)],
                        'estado_cred_final' => ['Normal', 'Alerta'][rand(0, 1)],
                    ]);

                    $this->command->line("  ✅ Control CRED {$numeroControl} creado");
                }
            }
        }
    }

    /**
     * Crear tamizaje neonatal
     */
    private function crearTamizaje($ninoId, $fechaNacimiento)
    {
        $existe = DB::table('tamizaje_neonatal')->where('id_niño', $ninoId)->exists();
        if ($existe) {
            $this->command->line("  ⏭️  Tamizaje ya existe para niño {$ninoId}");
            return;
        }

        $fecha29Dias = $fechaNacimiento->copy()->addDays(29);
        $fechaTamizaje = $fechaNacimiento->copy()->addDays(rand(1, 29));
        $edadTamizaje = $fechaNacimiento->diffInDays($fechaTamizaje);

        DB::table('tamizaje_neonatal')->insert([
            'id_niño' => $ninoId,
            'fecha_29_dias' => $fecha29Dias->format('Y-m-d'),
            'fecha_tam_neo' => $fechaTamizaje->format('Y-m-d'),
            'edad_tam_neo' => $edadTamizaje,
            'galen_fecha_tam_feo' => $fechaTamizaje->copy()->addDays(rand(1, 5))->format('Y-m-d'),
            'galen_dias_tam_feo' => rand(30, 35),
            'cumple_tam_neo' => ['SI', 'NO'][rand(0, 1)],
        ]);

        $this->command->line("  ✅ Tamizaje creado para niño {$ninoId}");
    }

    /**
     * Crear vacunas de recién nacido
     */
    private function crearVacunasRN($ninoId, $fechaNacimiento)
    {
        $existe = DB::table('vacuna_rn')->where('id_niño', $ninoId)->exists();
        if ($existe) {
            $this->command->line("  ⏭️  Vacunas RN ya existen para niño {$ninoId}");
            return;
        }

        $fechaBCG = $fechaNacimiento->copy()->addDays(rand(0, 7));
        $fechaHVB = $fechaNacimiento->copy()->addDays(rand(0, 7));

        DB::table('vacuna_rn')->insert([
            'id_niño' => $ninoId,
            'fecha_bcg' => $fechaBCG->format('Y-m-d'),
            'edad_bcg' => $fechaNacimiento->diffInDays($fechaBCG),
            'estado_bcg' => ['SI', 'NO'][rand(0, 1)],
            'fecha_hvb' => $fechaHVB->format('Y-m-d'),
            'edad_hvb' => $fechaNacimiento->diffInDays($fechaHVB),
            'estado_hvb' => ['SI', 'NO'][rand(0, 1)],
            'cumple_BCG_HVB' => ['SI', 'NO'][rand(0, 1)],
        ]);

        $this->command->line("  ✅ Vacunas RN creadas para niño {$ninoId}");
    }

    /**
     * Crear visitas domiciliarias
     */
    private function crearVisitasDomiciliarias($ninoId, $fechaNacimiento, $edadDias)
    {
        // Crear 1-3 visitas según la edad
        $numeroVisitas = min(3, max(1, (int)($edadDias / 30)));

        $visitasExistentes = DB::table('visitas_domiciliarias')
            ->where('id_niño', $ninoId)
            ->count();

        if ($visitasExistentes >= $numeroVisitas) {
            $this->command->line("  ⏭️  Visitas ya existen para niño {$ninoId}");
            return;
        }

        for ($i = $visitasExistentes + 1; $i <= $numeroVisitas; $i++) {
            $fechaVisita = $fechaNacimiento->copy()->addDays(rand(7, min($edadDias, 180)));

            DB::table('visitas_domiciliarias')->insert([
                'id_niño' => $ninoId,
                'grupo_visita' => ['Grupo A', 'Grupo B', 'Grupo C'][rand(0, 2)],
                'fecha_visita' => $fechaVisita->format('Y-m-d'),
                'numero_visitas' => $i,
            ]);
        }

        $this->command->line("  ✅ {$numeroVisitas} visita(s) domiciliaria(s) creada(s) para niño {$ninoId}");
    }

    /**
     * Crear datos extra
     */
    private function crearDatosExtra($ninoId, $nino)
    {
        $existe = DB::table('datos_extra')->where('id_niño', $ninoId)->exists();
        if ($existe) {
            $this->command->line("  ⏭️  Datos extra ya existen para niño {$ninoId}");
            return;
        }

        $redes = ['Red de Salud Lima Norte', 'Red de Salud Lima Sur', 'Red de Salud Lima Este'];
        $microredes = ['Microred 01', 'Microred 02', 'Microred 03'];
        $distritos = ['San Juan de Lurigancho', 'Comas', 'Independencia', 'Los Olivos', 'Carabayllo'];
        $provincias = ['Lima', 'Callao'];
        $departamentos = ['Lima'];
        $seguros = ['SIS', 'ESSALUD', 'Privado', 'Sin seguro'];
        $programas = ['Programa Juntos', 'Programa Qali Warma', 'Ninguno'];

        DB::table('datos_extra')->insert([
            'id_niño' => $ninoId,
            'red' => $redes[array_rand($redes)],
            'microred' => $microredes[array_rand($microredes)],
            'eess_nacimiento' => $nino->establecimiento ?? 'EESS ' . rand(1, 10),
            'distrito' => $distritos[array_rand($distritos)],
            'provincia' => $provincias[array_rand($provincias)],
            'departamento' => $departamentos[array_rand($departamentos)],
            'seguro' => $seguros[array_rand($seguros)],
            'programa' => $programas[array_rand($programas)],
        ]);

        $this->command->line("  ✅ Datos extra creados para niño {$ninoId}");
    }
}

