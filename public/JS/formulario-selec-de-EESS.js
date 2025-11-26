// ======== LISTAS COMPLETAS DE REDES, MICROREDES Y ESTABLECIMIENTOS ========

// Exponer data globalmente para que pueda ser usado en otros scripts
var data = {
  // RED 1: AGUAYTIA
  1: {
    "MICRORED AGUAYTIA": [
      { value: "EST_3_OCTUBRE", text: "3 DE OCTUBRE" },
      { value: "EST_CS_AGUAYTIA", text: "AGUAYTIA" },
      { value: "EST_ALTO_AGUAYTIA", text: "ALTO AGUAYTIA" },
      { value: "EST_BOQUERON", text: "BOQUERON" },
      { value: "EST_HUIPOCA", text: "HUIPOCA" },
      { value: "EST_LA_DIVISORIA", text: "LA DIVISORIA" },
      { value: "EST_MARISCAL_CACERES", text: "MARISCAL CACERES" },
      { value: "EST_MIGUEL_GRAU_SEMINARIO", text: "MIGUEL GRAU SEMINARIO" },
      { value: "EST_NUEVA_CHONTA", text: "NUEVA CHONTA" },
      { value: "EST_PREVISTO", text: "PREVISTO" },
      { value: "EST_PUERTO_AZUL", text: "PUERTO AZUL" },
      { value: "EST_PTDS_LOS_OLIVOS", text: "PUESTO DE SALUD LOS OLIVOS" },
      { value: "EST_PTDS_VALLE_DE_SION", text: "PUESTO DE SALUD VALLE DE SION" },
      { value: "EST_PTDS_YAMINO", text: "PUESTO DE SALUD YAMINO" },
      { value: "EST_SANTA_ANA", text: "SANTA ANA" },
      { value: "EST_SANTA_ROSA_DE_AGUAYTIA", text: "SANTA ROSA DE AGUAYTIA" },
      { value: "EST_SHAMBILLO", text: "SHAMBILLO" }
    ],
    "MICRORED SAN ALEJANDRO": [
      { value: "EST_CSMC_II_KA_BIMIA", text: "CENTRO DE SALUD MENTAL COMUNITARIO -II KA BIMIA" },
      { value: "EST_CS_SAN_ALEJANDRO", text: "CENTRO DE SALUD SAN ALEJANDRO" },
      { value: "EST_NUEVO_TAHUANTINSUYO", text: "NUEVO TAHUANTINSUYO" },
      { value: "EST_NUEVO_UCAYALI_KM_98", text: "NUEVO UCAYALI KM.98" },
      { value: "EST_PUERTO_NUEVO", text: "PUERTO NUEVO" },
      { value: "EST_PTDS_DE_SHANANTIA", text: "PUESTO DE SALUD DE SHANANTIA" },
      { value: "EST_PTDS_NUEVA_FLORIDA", text: "PUESTO DE SALUD NUEVA FLORIDA" },
      { value: "EST_PTDS_NUEVA_UNION_PALOMETA", text: "PUESTO DE SALUD NUEVA UNION PALOMETA" },
      { value: "EST_SAN_ALEJANDRO", text: "SAN ALEJANDRO" },
      { value: "EST_SAN_JUAN_KM_130", text: "SAN JUAN KM.130" },
      { value: "EST_SAN_PEDRO_DE_CHIO", text: "SAN PEDRO DE CHIO" },
      { value: "EST_SINCHI_ROCA", text: "SINCHI ROCA" }
    ]
  },
  // RED 2: ATALAYA
  2: {
    "MICRORED ATALAYA": [
      { value: "EST_ALTO_CHENCORENI", text: "ALTO CHENCORENI" },
      { value: "EST_CS_ATALAYA", text: "ATALAYA" },
      { value: "EST_CSMC_ATALAYA", text: "CENTRO DE SALUD MENTAL COMUNITARIO ATALAYA" },
      { value: "EST_CHEQUITAVO", text: "CHEQUITAVO" },
      { value: "EST_CHICOSA", text: "CHICOSA" },
      { value: "EST_COCANI", text: "COCANI" },
      { value: "EST_CS_HSPT_INTERCULTURAL_DE_ATALAYA", text: "HOSPITAL INTERCULTURAL DE ATALAYA" },
      { value: "EST_LA_FLORESTA", text: "LA FLORESTA" },
      { value: "EST_MALDONADILLO", text: "MALDONADILLO" },
      { value: "EST_MAPALCA", text: "MAPALCA" },
      { value: "EST_OBENTENI", text: "OBENTENI" },
      { value: "EST_PAOTI", text: "PAOPI" },
      { value: "EST_PTDS_LA_INMACULADA", text: "LA INMACULADA" },
      { value: "EST_RAMON_CASTILLA", text: "RAMON CATILLA" },
      { value: "EST_RIMA", text: "RIMA" },
      { value: "EST_SERJALI", text: "SERJALI" },
      { value: "EST_UNINI", text: "UNINI" }
    ],
    "MICRORED BOLOGNESI": [
      { value: "EST_BOLOGNESI", text: "BOLOGNESI" },
      { value: "EST_NUEVA_ITALIA", text: "NUEVA ITALIA" },
      { value: "EST_PTDS_NUEVO_PARAISO_TAHUANIA", text: "PUESTO DE SALUD NUEVO PARAISO TAHUANIA" },
      { value: "EST_SEMPAYA", text: "SEMPAYA" },
      { value: "EST_SHAHUAYA", text: "SHAHUAYA" },
      { value: "EST_TONIROMASHI", text: "TONIROMASHI" },
      { value: "EST_TUPAC_AMARU", text: "TUPAC AMARU" }
    ],
    "MICRORED RAYMONDI": [
      { value: "EST_PTDS_CHINCHENI", text: "PUESTO DE SALUD CHINCHENI" },
      { value: "EST_PTDS_LOS_OLIVOS_R", text: "PUESTO DE SALUD LOS OLIVOS" }
    ],
    "MICRORED SEPAHUA": [
      { value: "EST_BUFEO_POZO", text: "BUFEO POZO" },
      { value: "EST_PTDS_NUEVO_HORIZONTE", text: "PUESTO DE SALUD NUEVO HORIZONTE" },
      { value: "EST_PUIJA", text: "PUIJA" },
      { value: "EST_SANTA_ROSA_DE_SERJALI", text: "SANTA ROSA DE SERJALI" },
      { value: "EST_SEPAHUA", text: "SEPAHUA" }
    ]
  },
  // RED 3: BAP-CURARAY
  3: {
    "MICRORED BAP-CURARAY": [
      { value: "EST_BAP_CURARAY", text: "BAP_CURARAY" }
    ]
  },
  // RED 4: CORONEL PORTILLO
  4: {
    "MICRORED 9 DE OCTUBRE": [
      { value: "EST_09_DE_OCTUBRE", text: "09 DE OCTUBRE" },
      { value: "EST_BELLAVISTA", text: "BELLAVISTA" },
      { value: "EST_CSMC_PROCERES_DE_LA_INDEPENDENCIA", text: "CENTRO DE SALUD MENTAL COMUNITARIO PROCERES DE LA INDEPENDENCIA" },
      { value: "EST_CHANCAY", text: "CHANCAY" },
      { value: "EST_DOS_DE_MAYO_KM_12", text: "DOS DE MAYO KM 12" },
      { value: "EST_FRATERNIDAD", text: "FRATERNIDAD" },
      { value: "EST_LA_FLORIDA", text: "LA FLORIDA" },
      { value: "EST_MICAELA_BASTIDAS", text: "MICAELA BASTIDAS" },
      { value: "EST_NUESTRA_SENORA_DE_LAS_MERCEDES", text: "NUESTRA SENORA DE LAS MERCEDES" },
      { value: "EST_NUEVA_MAGDALENA", text: "NUEVA MAGDALENA" },
      { value: "EST_NUEVA_PUCALLPA_KM_13", text: "NUEVA PUCALLPA KM 13.500" },
      { value: "EST_NUEVO_BOLOGNESI", text: "NUEVO BOLOGNESI" },
      { value: "EST_SANTA_ELENA", text: "SANTA ELENA" },
      { value: "EST_TUPAC_AMARU_9", text: "TUPAC AMARU" },
      { value: "EST_YANAMAYO", text: "YANAMAYO" }
    ],
    "MICRORED IPARIA": [
      { value: "EST_AMAQUIRIA", text: "AMAQUIRIA" },
      { value: "EST_CACO_MACAYA", text: "CACO MACAYA" },
      { value: "EST_COLONIA_DEL_CACO", text: "COLONIA DEL CACO" },
      { value: "EST_CUNCHURI", text: "CUNCHURI" },
      { value: "EST_CURIACA_DEL_CACO", text: "CURIACA DEL CACO" },
      { value: "EST_GALILEA", text: "GALILEA" },
      { value: "EST_IPARIA", text: "IPARIA" },
      { value: "EST_NUEVA_SAMARIA", text: "NUEVA SAMARIA" },
      { value: "EST_NUEVO_AHUAYPA", text: "NUEVO AHUAYPA" },
      { value: "EST_NUEVA_NAZARETH", text: "NUEVA NAZARETH" },
      { value: "EST_PUEBLO_NUEVO_DEL_CACO", text: "PUEBLO NUEVO DEL CACO" },
      { value: "EST_PUERTO_BELEN", text: "PUERTO BELEN" },
      { value: "EST_PUERTO_NUEVO_I", text: "PUERTO NUEVO" },
      { value: "EST_RUNUYA", text: "RUNUYA" },
      { value: "EST_SANTA_ROSA_DE_SHESHEA", text: "SANTA ROSA DE SHESHEA" },
      { value: "EST_SHARARA", text: "SHARARA" },
      { value: "EST_UTUCURO", text: "UTUCURO" }
    ],
    "MICRORED MASISEA": [
      { value: "EST_BELLA_FLOR", text: "BELLA FLOR" },
      { value: "EST_CAIMITO", text: "CAIMITO" },
      { value: "EST_CHARASMANA", text: "CHARASMANA" },
      { value: "EST_ISLA_LIBERTAD", text: "ISLA LIBERTAD" },
      { value: "EST_JUNIN_PABLO", text: "JUNIN PABLO" },
      { value: "EST_MASISEA", text: "MASISEA" },
      { value: "EST_NOHAYA", text: "NOHAYA" },
      { value: "EST_NUEVO_HORIZONTE", text: "NUEVO HORIZONTE" },
      { value: "EST_NUEVO_PARAISO_M", text: "NUEVO PARAISO" },
      { value: "EST_PUERTO_ALEGRE", text: "PUERTO ALEGRE" },
      { value: "EST_PUTAYA", text: "PUTAYA" },
      { value: "EST_SAN_PEDRO_DE_INAMAPUYA", text: "SAN PEDRO DE INAMAPUYA" },
      { value: "EST_SANTA_FE_DE_INAMAPUYA", text: "SANTA FE DE INAMAPUYA" },
      { value: "EST_SANTA_ROSA_DE_DINAMARCA", text: "SANTA ROSA DE DINAMARCA" },
      { value: "EST_SANTA_ROSA_DE_MASISEA", text: "SANTA ROSA DE SHESHEA" },
      { value: "EST_SANTA_ROSA_DE_TAMAYA", text: "SANTA ROSA DE TAMAYA" },
      { value: "EST_VARGAS_GUERRA", text: "VARGAS GUERRA" },
      { value: "EST_VINUNCURO", text: "VINUNCURO" },
      { value: "EST_VISTA_ALEGRE_DE_PACHITEA", text: "VISTA ALEGRE DE PACHITEA" }
    ],
    "MICRORED PURUS": [
      { value: "EST_BALTA", text: "BALTA " },
      { value: "EST_GASTA_BALA", text: "GASTA BALA" },
      { value: "EST_MIGUEL_GRAU", text: "MIGUEL GRAU" },
      { value: "EST_NUEVA_LUZ", text: "NUEVA LUZ" },
      { value: "EST_PALESTINA", text: "PALESTINA" },
      { value: "EST_PURUS", text: "PURUS" },
      { value: "EST_SAN_BERNARDO", text: "SAN BERNARDO" },
      { value: "EST_SAN_MARCOS", text: "SAN MARCOS" }
    ],
    "MICRORED SAN FERNANDO": [
      { value: "EST_07_DE_JUNIO", text: "07 DE JUNIO" },
      { value: "EST_ABUJAO", text: "ABUJAO" },
      { value: "EST_CDSMC_NUEVO_AMANECER", text: "CENTRO DE SALUD MENTAL COMUNITARIO NUEVO AMANECER" },
      { value: "EST_EXITO", text: "EXITO" },
      { value: "EST_JUAN_VELASCO_ALVARADO", text: "JUAN VELASCO ALVARADO" },
      { value: "EST_LUZ_Y_PAZ", text: "LUZ Y PAZ" },
      { value: "EST_MANANTAY", text: "MANANTAY" },
      { value: "EST_MAZARAY", text: "MAZARAY" },
      { value: "EST_NUEVA_ALIANZA", text: "NUEVA ALIANZA" },
      { value: "EST_NUEVA_BETANIA", text: "NUEVA BETANIA" },
      { value: "EST_NUEVO_BAGAZAN", text: "NUEVO BAGAZAN" },
      { value: "EST_NUEVO_SAN_JUAN", text: "NUEVO SAN JUAN" },
      { value: "EST_NUEVO_SAPOSOA", text: "NUEVO SAPOSOA" },
      { value: "EST_NUEVO_UTUQUINIA", text: "NUEVO UTUQUINIA" },
      { value: "EST_PANAILLO", text: "PANAILLO" },
      { value: "EST_PATRIA_NUEVA", text: "PATRIA NUEVA" },
      { value: "EST_PUCALLPILLO", text: "PUCALLPILLO" },
      { value: "EST_PUERTO_BETHEL", text: "PUERTO BETHEL" },
      { value: "EST_SAN_ANTONIO", text: "SAN ANTONIO" },
      { value: "EST_SAN_FERNANDO", text: "SAN FERNANDO" },
      { value: "EST_SAN_MIGUEL_DE_CALLERIA", text: "SAN MIGUEL DE CALLERIA" },
      { value: "EST_SANTA_CARMELA_DE_MASHANGAY", text: "SANTA CARMELA DE MASHANGAY" },
      { value: "EST_SANTA_ISABEL_DE_BAHUANISHO", text: "SANTA ISABEL DE BAHUANISHO" },
      { value: "EST_SANTA_ROSA_DE_ABUJAO", text: "SANTA ROSA DE ABUJAO" },
      { value: "EST_SANTA_SOFIA", text: "SANTA SOFIA" },
      { value: "EST_SANTA_TERESA_DE_SHINUYA", text: "SANTA TERESA DE SHINUYA" },
      { value: "EST_SANTO_DOMINGO_DE_MASHANGAY", text: "SANTO DOMINGO DE MASHANGAY" },
      { value: "EST_SMAHP_SAN_FERNANDO", text: "SERVICIO MEDIDO DE APOYO HOGAR PPROTEGIDO SAN FERNANDO" },
      { value: "EST_TACSHITEA", text: "TACSHITEA" }
    ],
    "MICRORED YURUA": [
      { value: "EST_BREU", text: "BREU" },
      { value: "EST_DULCE_GLORIA", text: "DULCE GLORIA" },
      { value: "EST_SAWAWO", text: "SAWAWO" }
    ]
  },
  // RED 5: ESSALUD
  5: {
    "MICRORED ESSALUD": [
      { value: "EST_HOSPITAL_II_PUCALLPA_ESSALUD", text: "HOSPITAL II PUCALLPA ESSALUD" },
      { value: "EST_PM_LAS_ALAMEDAS", text: "POSTA MEDICA LAS ALAMEDAS" },
      { value: "EST_PM_ATALAYA", text: "POSTA MEDICA ATALAYA" },
      { value: "EST_PM_CAMPO_VERDE", text: "POSTA MEDICA CAMPO VERDE" },
      { value: "EST_PM_DE_MANANTAY", text: "POSTA MEDICA DE MANANTAY" },
      { value: "EST_PM_ESSALUD_AGUAYTIA", text: "POSTA MEDICA ESSALUD AGUAYTIA" }
    ]
  },
  // RED 6: FEDERICO BASADRE - YARINACOCHA
  6: {
    "MICRORED CAMPO VERDE": [
      { value: "EST_AGUA_BLANCA", text: "AGUA BLANCA" },
      { value: "EST_ALTO_09_DE_OCTUBRE", text: "ALTO 09 DE OCTUBRE" },
      { value: "EST_ANTONIO_RAYMONDI", text: "ANTONIO RAYMONDI" },
      { value: "EST_CAMPO_VERDE", text: "CAMPO VERDE" },
      { value: "EST_EL_PORVENIR_KM_25", text: "EL PORVENIR KM.25" },
      { value: "EST_LA_MERCED_DE_NESHUYA", text: "LA MERCED DE NESHUYA" },
      { value: "EST_LA_VICTORIA", text: "LA VICTORIA" },
      { value: "EST_LOS_PINOS", text: "LOS PINOS" },
      { value: "EST_NUEVA_ESPERANZA", text: "NUEVA ESPERANZA" },
      { value: "EST_NUEVA_TUNUYA", text: "NUEVA TUNUYA" },
      { value: "EST_PTDS_LAS_MERCEDES_KM_42", text: "PUESTO DE SALUD LAS MERCEDES KM.42" },
      { value: "EST_PIMENTAL", text: "PIMENTAL" },
      { value: "EST_PUEBLO_LIBRE", text: "PUEBLO LIBRE" },
      { value: "EST_PTDS_ALTO_MANANTAY", text: "PUESTO DE SALUD ALTO MANANTAY" },
      { value: "EST_PTDS_03_DE_DICIEMBRE", text: "PUESTO DE SALUD 3 DE DICIEMBRE" },
      { value: "EST_PTDS_AMAKELLA", text: "PUESTO DE SALUD AMAKELLA" },
      { value: "EST_SAN_JOSE_KM_26", text: "SAN JOSE KM.26" },
      { value: "EST_SAN_MARTIN_DE_MOJARAL", text: "SAN MARTIN DE MOJARAL" },
      { value: "EST_SAN_PEDRO_KM_47", text: "SAN PEDRO KM.47" },
      { value: "EST_SANTA_ROSA_KM_50_CFB", text: "SANTA ROSA KM.50 CFB" },
      { value: "EST_SENOR_DE_LOS_MILAGROS_KM_24", text: "SENOR DE LOS MILAGROS KM 24" },
      { value: "EST_SIMON_BOLIVAR", text: "SIMON BOLIVAR" },
      { value: "EST_TIERRA_BUENA", text: "TIERRA BUENA" },
      { value: "EST_YERBAS_BUENAS", text: "YERBAS BUENAS" }
    ],
    "MICRORED CURIMANA": [
      { value: "EST_BELLO_HORIZONTE", text: "BELLO HORIZONTE" },
      { value: "EST_CURIMANA", text: "CURIMANA" },
      { value: "EST_LAS_MALVINAS", text: "LAS MALVINAS" },
      { value: "EST_PUEBLO_LIBRE_CURIMANA", text: "PUEBLO LIBRE CURIMANA" },
      { value: "EST_PTDS_MERIBA", text: "PUESTO DE SALUD MERIBA" },
      { value: "EST_SAN_JUAN_DE_TAHUAPOA", text: "SAN JUAN DE TAHUAPOA" },
      { value: "EST_ZORRILLOS", text: "ZORRILLOS" }
    ],
    "MICRORED MONTE ALEGRE": [
      { value: "EST_EL_MILAGRO_KM_83", text: "EL MILAGRO KM.83" },
      { value: "EST_LA_UNION", text: "LA UNION" },
      { value: "EST_MONTE_ALEGRE_NESHUYA", text: "MONTE ALEGRE NESHUYA" },
      { value: "EST_MONTE_DE_LOS_OLIVOS", text: "MONTE DE LOS OLIVOS" },
      { value: "EST_NUEVO_SAN_JUAN_KM_69", text: "NUEVO SAN JUAN KM.69" },
      { value: "EST_PTDS_MAR_DE_PLATA", text: "PUESTO DE SALUD MAR DE PLATA" },
      { value: "EST_PTDS_NOLBERTH_DE_ALTO_ARUYA", text: "PUESTO DE SALUD NOLBERTH DE ALTO ARUYA" },
      { value: "EST_SAN_JUAN_KM_71", text: "SAN JUAN KM.71" },
      { value: "EST_SANTA_ROSA_DE_GUINEA", text: "SANTA ROSA DE GUINEA" },
      { value: "EST_VILLA_DEL_CAMPO", text: "VILLA DEL CAMPO" },
      { value: "EST_VIRGEN_DEL_CARMEN", text: "VIRGEN DEL CARMEN" },
      { value: "EST_VON_HUMBOLTD", text: "VON HUMBOLTD" }
    ],
    "MICRORED NUEVA REQUENA": [
      { value: "EST_ESPERANZA_DE_AGUAYTIA", text: "ESPERANZA DE AGUAYTIA" },
      { value: "EST_JUVENTUD", text: "JUVENTUD" },
      { value: "EST_MIRAFLORES", text: "MIRAFLORES" },
      { value: "EST_NARANJAL", text: "NARANJAL" },
      { value: "EST_NUEVA_REQUENA", text: "NUEVA REQUENA" },
      { value: "EST_NUEVO_PIURA", text: "NUEVO PIURA" },
      { value: "EST_PTDS_LA_PERLA_DE_SANJA_SECA", text: "PUESTO DE SALUD LA PERLA DE SNAJ SECA" },
      { value: "EST_SAN_PABLO_DE_JUANTIA", text: "SAN PABLO DE JUANTIA" },
      { value: "EST_SANTA_CARLA_DE_UCHUNYA", text: "SANTA CARLA DE UCHUNYA" },
      { value: "EST_SARITA_COLONIA", text: "SARITA COLONIA" },
      { value: "EST_SHAMBO_PORVENIR", text: "SHAMBO PORVENIR" }
    ],
    "MICRORED NUEVO PARAISO": [
      { value: "EST_CENTRO_AMERICA", text: "CENTRO AMERICA" },
      { value: "EST_CSMC_COMUNITARIO_UNIVERSITARIO_MAYUSHIN", text: "CENTRO DE SALUD MENTAL COMUNITARIO UNIVERSITARIO MAYUSHIN" },
      { value: "EST_CSMC_COMUNITARIO_BENA_MANATI", text: "CENTRO DE SALUD MENTAL COMUNITARIO BENA MANATI" },
      { value: "EST_CLAS_TUPAC_AMARU", text: "CLAS TUPAC AMARU" },
      { value: "EST_DOS_DE_MAYO", text: "DOS DE MAYO" },
      { value: "EST_HOGAR_PROTEGIDO_CALLERIA", text: "HOGAR PROTEGIDO CALLERIA" },
      { value: "EST_HUSARES_DEL_PERU", text: "HUSARES DEL PERU" },
      { value: "EST_JOSE_OLAYA", text: "JOSE OLAYA" },
      { value: "EST_NUEVO_PARAISO", text: "NUEVO PARAISO" },
      { value: "EST_SANIDAD_AEREA", text: "SANIDAD AEREA" },
      { value: "EST_SANTA_TERESITA", text: "SANTA TERESITA" },
      { value: "EST_SHIRAMBARI", text: "SHIRAMBARI" }
    ],
    "MICRORED SAN JOSE DE YARINACOCHA": [
      { value: "EST_CASHIVOCOCHA", text: "CASHIVOCOCHA" },
      { value: "EST_CLAS_BELLAVISTA_DE_YARINACOCHA", text: "CLAS BELLAVISTA DE YARINACOCHA" },
      { value: "EST_CLAS_DE_SAN_JUAN_YARINACOCHA", text: "CLAS DE SAN JUAN YARINACOCHA" },
      { value: "EST_CLAS_TUPAC_AMARU_Y", text: "CLAS TUPAC AMARU" },
      { value: "EST_CLAS_SAN_PABLO_DE_TUSHMO", text: "CLAS SAN PABLO DE TUSHMO" },
      { value: "EST_ESPERANZA_DE_PANAILLO", text: "ESPERANZA DE PANAILLO" },
      { value: "EST_HUITOCOCHA", text: "HUITOCOCHAU" },
      { value: "EST_LEONCIO_PRADO", text: "LEOCNIO PRADO" },
      { value: "EST_MARISCAL_SUCRE", text: "MARISCAL SUCRE" },
      { value: "EST_NUEVA_ALEJANDRIA", text: "NUEVA ALEJANDRIA" },
      { value: "EST_NUEVA_LUZ_DE_FATIMA", text: "NUEVA LUZ DE FATIMA" },
      { value: "EST_NUEVA_UNION", text: "NUEVA UNION" },
      { value: "EST_PTDS_HUITOCOCHA", text: "PTDS HUITOCOCHA" },
      { value: "EST_SAN_FRANCISCO_DE_YARINACOCHA", text: "SAN FRANCISCO DE YARINACOCHA" },
      { value: "EST_SAN_JOSE_DE_YARINACOCHA", text: "SAN JOSE DE YARINACOCHA" },
      { value: "EST_SANTA_ROSA", text: "SANTA ROSA" },
      { value: "EST_SENOR_DE_LOS_MILAGROS", text: "SENIOR DE LOS MILAGROS" }
    ]
  },
  // RED 7: HOSPITAL AMAZONICO - YARINACOCHA
  7: {
    "MICRORED HOSPITAL AMAZONICO YARINACOCHA": [
      { value: "EST_HOSPITAL_AMAZONICO_YARINACOCHA", text: "HOSPITAL AMAZONICO YARINACOCHA" }
    ]
  },
  // RED 8: HOSPITAL REGIONAL DE PUCALLPA
  8: {
    "MICRORED HOSPITAL REGIONAL DE PUCALLPA": [
      { value: "EST_HOSPITAL_REGIONAL_DE_PUCALLPA", text: "HOSPITAL REGIONAL DE PUCALLPA" }
    ]
  },
  // RED 9: NO PERTENECE A NINGUNA RED
  9: {
    "MICRORED NO PERTENECE A NINGUNA MICRORED": [
      { value: "EST_CS", text: "HOSPITAL REGIONAL DE PUCALLPA" },
      { value: "EST_CES_BTC_LAB_DE_ESPECIALIDAD_MEDICA", text: "BOTICAS Y LABORATORIOS DE ESPECIALIDAD MEDICA E.I.R.L." },
      { value: "EST_CES_CASA_DE_SALUD_BUEN_SAMARITANO", text: "CASA DE SALUD BUEN SAMARITANO" },
      { value: "EST_CDIA_NOSTRA_SIGNORA_DI_LOURDES_SAC", text: "CENTRO DE DIALISIS NOSTRA SIGNORA DI LOURDES SAC" },
      { value: "EST_CM_OCUPACIONAL_PUCALLPA_SAC", text: "CENTRO DE MEDICINA OCUPACIONAL PUCALLPA SAC" },
      { value: "EST_CSMC_MAY-USHIN", text: "CENTRO DE SALUD MENTAL COMUNITARIO MAY-USHIN" },
      { value: "EST_CSMC_PROCERES_DE_LA_INDEPENDENCIA_9", text: "CENTRO DE SALUD MENTAL COMUNITARIO PROCERES DE LA INDEPENDENCIA" },
      { value: "EST_CS_MILITAR_PUCALLPA", text: "CENTRO DE SALUD MILITAR PUCALLPA" },
      { value: "EST_CD_POR_IMAGENES", text: "CENTRO DIAGNOSTICO POR IMAGENES" },
      { value: "EST_CM_DE_BIENESTAR_YARINACOCHA_SAC", text: "NUEVA ALIANZA" },
      { value: "EST_CM_DEL_ROSARIO_SRL", text: "CENTRO MEDICO DEL ROSARIO SRL" },
      { value: "EST_CM_DR_CONDE_EIRL", text: "CENTRO MEDICO DR. CONDE E.I.R.L." },
      { value: "EST_CM_ESPECIALISTA_MONSERRATE_EIRL", text: "CENTRO MEDICO ESPECIALISTA MONSERRATE EIRL" },
      { value: "EST_CM_ESPECIALIZADO_DEL_ORIENTE_SAC", text: "CENTRO MEDICO ESPECIALIZADO DEL ORIENTE SAC" },
      { value: "EST_CM_IBAZETA_LAB_SAC", text: "CENTRO MEDICO IBAZETA LAB S.A.C." },
      { value: "EST_CM_MUNICIPAL_DE_ATENCION_BASICA", text: "CENTRO MEDICO MUNICIPAL DE ATENCION BASICA" },
      { value: "EST_CM_OCUPACIONAL_ATALAYA", text: "CENTRO MEDICO OCUPACIONAL ATALAYA" },
      { value: "EST_CM_ODONTOLOGICO_AMERICANO_SAC", text: "CENTRO MEDICO ODONTOLOGICO AMERICANO S.A.C" },
      { value: "EST_CM_ODONTOLOGICO_RAYMONDI", text: "CENTRO MEDICO ODONTOLOGICO RAYMONDI" },
      { value: "EST_CM_QUIRURGICO_JUAN_PABLO_II", text: "CENTRO MEDICO QUIRURGICO JUAN PABLO II" },
      { value: "EST_CM_SALVADOR_ALLENDE_EIRL", text: "CENTRO MEDICO SALVADOR ALLENDE E.I.R.L" },
      { value: "EST_CM_TERAN_EIRL", text: "CENTRO MEDICO TERAN EIRL" },
      { value: "EST_CM_YARINA_SAC", text: "CENTRO MEDICO YARINA S.A.C" },
      { value: "EST_CM_ZELADA", text: "CENTRO MEDICO ZELADA" },
      { value: "EST_CENTRO_NEFROUROLOGICO_DEL_ORIENTE_SAC", text: "CENTRO NEFROUROLOGICO DEL ORIENTE S.A.C." },
      { value: "EST_CENTRO_OFTALMOLOGICO_LUSS_EIRL", text: "CENTRO OFTALMOLOGICO LUSS E.I.R.L." },
      { value: "EST_CENTRO_PREVENTIVO_INMUNOLOGICO_PREVENVAC_SRL", text: "CENTRO PREVENTIVO INMUNOLOGICO PREVENVAC S.R.L." },
      { value: "EST_CLINICA_AMAZONICA_EIRL", text: "CLINICA AMAZONICA E.I.R.L" },
      { value: "EST_CLINICA_DEL_RINION_SELVA_EIRL", text: "CLINICA DEL RINION SELVA EIRL" },
      { value: "EST_CLINICA_DENTAL_ASENCIOS_SAC", text: "CLINICA DENTAL ASENCIOS S.A.C" },
      { value: "EST_CLINICA_DENTAL_ORTIZ_EIRL", text: "CLINICA DENTAL ORTIZ E.I.R.L" },
      { value: "EST_CLINICA_DENTAL_SAN_MIGUEL", text: "CLINICA DENTAL SAN MIGUEL" },
      { value: "EST_CLINICA_ESMEDIC_EIRL", text: "CLINICA ESMEDIC E.I.R.L." },
      { value: "EST_CLINICA_ESPECIALIZADA_AMAZONICA_SAC", text: "CLINICA ESPECIALIZADA AMAZONICA SAC" },
      { value: "EST_CLINICA_FERNANDEZ_EIRL", text: "CLINICA FERNANDEZ E.I.R.L." },
      { value: "EST_CLINICA_JUAN_PABLO_II", text: "CLINICA JUAN PABLO II" },
      { value: "EST_CLINICA_LAS_AMERICAS", text: "CLINICA LAS AMERICAS" },
      { value: "EST_CLINICA_ODONTOLOGICA_GUERREROS", text: "CLINICA ODONTOLOGICA GUERREROS" },
      { value: "EST_CLINICA_SANTA_ANITA_PUCALLPA", text: "CLINICA SANTA ANITA PUCALLPA" },
      { value: "EST_CLINICA_TERESACALCUTA_MEDIC_SAC", text: "CLINICA TERESACALCUTA MEDIC S.A.C." },
      { value: "EST_CLONICA_MONTE_HOREB_SA", text: "CLÓNICA MONTE HOREB S.A." },
      { value: "EST_CLONICA_ODONTOLOGICA_DENTO_SALUD_EIRL", text: "CLÓNICA ODONTOLOGICA DENTO SALUD E.I.R.L" },
      { value: "EST_CONSORCIO_DE_INVERSIONES_SANTA_CRUZ_EIRL", text: "CONSORCIO DE INVERSIONES SANTA CRUZ E.I.R.L" },
      { value: "EST_CONSULTORIO_MEDICO_DANSALUD", text: "CONSULTORIO MEDICO DANSALUD" },
      { value: "EST_CONSULTORIO_MEDICO_SALAVERRY", text: "CONSULTORIO MEDICO SALAVERRY" },
      { value: "EST_CLINICA_ODONTOLOGICA_GUERREROS_2", text: "CLINICA ODONTOLOGICA GUERREROS" },
      { value: "EST_ESMEDIC_EIRL_SEDE_SALAVERRY", text: "ESMEDIC E.I.R.L SEDE SALAVERRY" },
      { value: "EST_GLOBAL_MEDIC", text: "GLOBAL MEDIC" },
      { value: "EST_LAB_DE_REFERENCIA_REGIONAL_EN_SALUD_PUBLICA_UCAYALI", text: "LABORATORIO DE REFERENCIA REGIONAL EN SALUD PUBLICA UCAYALI" },
      { value: "EST_MEDICAL_CENTER_PUCALLPA_EIRL", text: "MEDICAL CENTER PUCALLPA E.I.R.L" },
      { value: "EST_NATURA_ANALITICA_SAC", text: "NATURA ANALITICA S.A.C" },
      { value: "EST_MEDIMAGEN_SAC", text: "MEDIMAGEN S.A.C" },
      { value: "EST_MISION_SUIZA_EN_EL_PERU", text: "MISION SUIZA EN EL PERU" },
      { value: "EST_OPTICA_PUCALLPA", text: "OPTICA PUCALLPA" },
      { value: "EST_PENITENCIARIO_DE_PUCALLPA", text: "PENITENCIARIO DE PUCALLPA" },
      { value: "EST_POLICLINICO_BIOSALUD_ATALAYA_EIRL", text: "POLICLINICO BIOSALUD ATALAYA E.I.R.L." },
      { value: "EST_POLICLINICO_MARÖA_BEATRIZ_SRL", text: "POLICLINICO MARÖA BEATRIZ S.R.L" },
      { value: "EST_POLICLINICO_MAS_SALUD_EIRL", text: "POLICLINICO MAS SALUD E.I.R.L." },
      { value: "EST_POLICLINICO_MATERNO_INFANTIL_CAYETANO_HEREDIA_EIRL", text: "POLICLINICO MATERNO INFANTIL CAYETANO HEREDIA E.I.R.L" },
      { value: "EST_POLICLINICO_MISIONERO_EIRL", text: "POLICLINICO MISIONERO E.I.R.L." },
      { value: "EST_POLICLINICO_REGIONAL_EIRL", text: "POLICLINICO REGIONAL E.I.R.L." },
      { value: "EST_POLICLINICO_SAN_NORBERTO", text: "POLICLINICO SAN NORBERTO" },
      { value: "EST_POLICLINICO_SANIDAD_DE_LA_POLICIA_NACIONAL_DEL_PERU", text: "POLICLINICO SANIDAD DE LA POLICIA NACIONAL DEL PERU" },
      { value: "EST_PTDS_UMAR_3_CANTAGALLO", text: "PUESTO DE SALUD UMAR 3 CANTAGALLO" },
      { value: "EST_PTDS_UMAR_5_BREU", text: "PUESTO DE SALUD UMAR 5 BREU" },
      { value: "EST_PTDS_UMAR_6_PURUS", text: "PUESTO DE SALUD UMAR 6 PURUS" },
      { value: "EST_REPRESENTACIONES_SAN_BORJA_SAC", text: "REPRESENTACIONES SAN BORJA S.A.C." },
      { value: "EST_SALUMEDIC_SAC", text: "SALUMEDIC SAC" },
      { value: "EST_SANIDAD_DE_LA_ESTACION_NAVAL_DE_PUCALLPA", text: "SANIDAD DE LA ESTACION NAVAL DE PUCALLPA" },
      { value: "EST_SANIDAD_DEL_ALA_AEREA_NUMERO_4", text: "SANIDAD DEL ALA AEREA NUMERO 4" },
      { value: "EST_SERVICIOS_GENERALES_SERFARMED_SYS_SA", text: "SERVICIOS GENERALES SERFARMED SYS S.A." },
      { value: "EST_SISTEMA_DE_ATENCION_MOVIL_DE_URGENCIAS_SAMU", text: "SISTEMA DE ATENCION MOVIL DE URGENCIAS (SAMU)" }
    ]
  }
};

// ======== ELEMENTOS DEL DOM ========
const redSelect = document.getElementById("codigoRed");
const microredSelect = document.getElementById("codigoMicrored");
const eessSelect = document.getElementById("idEstablecimiento");

// ======== FUNCIÓN PARA LIMPIAR Y RESETEAR SELECTS ========
function resetSelect(select, placeholder) {
  select.innerHTML = `<option value="">${placeholder}</option>`;
  select.value = "";
}

// ======== EVENTO: CAMBIO DE RED ========
redSelect.addEventListener("change", () => {
  const redValue = redSelect.value;
  // Limpiar microrred y establecimiento
  resetSelect(microredSelect, "Seleccione una Microred");
  resetSelect(eessSelect, "Seleccione un Establecimiento");
  // Deshabilitar selects dependientes
  microredSelect.disabled = true;
  eessSelect.disabled = true;
  // Si se seleccionó una red válida
  if (redValue && data[redValue]) {
    // Habilitar microrred
    microredSelect.disabled = false;
    // Llenar microrredes
    Object.keys(data[redValue]).forEach((microredNombre) => {
      const option = document.createElement("option");
      option.value = microredNombre;
      option.textContent = microredNombre;
      microredSelect.appendChild(option);
    });
  }
});

// ======== EVENTO: CAMBIO DE MICRORED ========
microredSelect.addEventListener("change", () => {
  const redValue = redSelect.value;
  const microredValue = microredSelect.value;
  // Limpiar establecimiento
  resetSelect(eessSelect, "Seleccione un Establecimiento");
  // Deshabilitar establecimiento
  eessSelect.disabled = true;
  // Si se seleccionó una microrred válida
  if (redValue && microredValue && data[redValue] && data[redValue][microredValue]) {
    // Habilitar establecimiento
    eessSelect.disabled = false;
    // Llenar establecimientos
    data[redValue][microredValue].forEach((establecimiento) => {
      const option = document.createElement("option");
      option.value = establecimiento.value;
      option.textContent = establecimiento.text;
      eessSelect.appendChild(option);
    });
  }
});

// ======== INICIALIZACIÓN ========
// Asegurar que los selects dependientes estén deshabilitados al cargar
if (document.readyState === "loading") {
  document.addEventListener("DOMContentLoaded", () => {
    microredSelect.disabled = true;
    eessSelect.disabled = true;
  });
} else {
  microredSelect.disabled = true;
  eessSelect.disabled = true;
}





