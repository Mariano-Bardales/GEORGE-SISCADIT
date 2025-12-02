<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Nino;
use App\Models\ControlMenor1;
use Carbon\Carbon;

class RecalcularEstadosControles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'controles:recalcular-estados';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recalcula los estados de todos los controles CRED basándose en los rangos permitidos';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔄 Recalculando estados de controles CRED...');
        
        // Rangos CRED mensual
        $rangosCRED = [
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
        
        $controles = ControlMenor1::whereNotNull('edad')->get();
        $total = $controles->count();
        $actualizados = 0;
        $sinCambio = 0;
        $errores = 0;
        
        $bar = $this->output->createProgressBar($total);
        $bar->start();
        
        foreach ($controles as $control) {
            try {
                $nino = Nino::find($control->id_niño);
                if (!$nino || !$nino->fecha_nacimiento) {
                    $bar->advance();
                    continue;
                }
                
                $numeroControl = $control->numero_control;
                $rango = $rangosCRED[$numeroControl] ?? null;
                
                if (!$rango) {
                    $bar->advance();
                    continue;
                }
                
                $edadDias = (int)$control->edad;
                $estadoAnterior = $control->estado;
                $estadoNuevo = 'SEGUIMIENTO';
                
                // Calcular nuevo estado
                if ($edadDias >= $rango['min'] && $edadDias <= $rango['max']) {
                    $estadoNuevo = 'CUMPLE';
                } elseif ($edadDias > $rango['max']) {
                    $estadoNuevo = 'NO CUMPLE';
                } else {
                    $estadoNuevo = 'SEGUIMIENTO';
                }
                
                // Normalizar estados anteriores (PENDIENTE, pendiente, cumple, etc.)
                $estadoAnteriorNormalizado = strtoupper(trim($estadoAnterior ?? ''));
                if ($estadoAnteriorNormalizado === 'PENDIENTE' || $estadoAnteriorNormalizado === '') {
                    $estadoAnteriorNormalizado = 'SEGUIMIENTO';
                } elseif ($estadoAnteriorNormalizado === 'CUMPLE' || $estadoAnteriorNormalizado === 'cumple') {
                    $estadoAnteriorNormalizado = 'CUMPLE';
                } elseif ($estadoAnteriorNormalizado === 'NO CUMPLE' || $estadoAnteriorNormalizado === 'no_cumple' || $estadoAnteriorNormalizado === 'NO_CUMPLE') {
                    $estadoAnteriorNormalizado = 'NO CUMPLE';
                }
                
                // Actualizar si es diferente (comparar normalizados)
                if ($estadoAnteriorNormalizado !== $estadoNuevo) {
                    $control->estado = $estadoNuevo;
                    $control->save();
                    $actualizados++;
                    $this->line("\n   📝 Control ID {$control->id} (Control {$numeroControl}): '{$estadoAnterior}' → '{$estadoNuevo}' (Edad: {$edadDias} días, Rango: {$rango['min']}-{$rango['max']})");
                } else {
                    $sinCambio++;
                }
                
            } catch (\Exception $e) {
                $errores++;
                $this->error("\n❌ Error en control ID {$control->id}: " . $e->getMessage());
            }
            
            $bar->advance();
        }
        
        $bar->finish();
        $this->newLine();
        
        $this->info("✅ Recalculación completada:");
        $this->line("   - Total de controles procesados: {$total}");
        $this->line("   - Estados actualizados: {$actualizados}");
        $this->line("   - Estados sin cambio: {$sinCambio}");
        $this->line("   - Errores: {$errores}");
        
        return Command::SUCCESS;
    }
}

