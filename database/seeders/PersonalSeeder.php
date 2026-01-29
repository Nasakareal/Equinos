<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Personal;

class PersonalSeeder extends Seeder
{
    public function run(): void
    {
        $data = [

            // ===================== TURNO A =====================

            [
                'grado'=>'ENCARGADO','cuip'=>'GOOF900420H164247296','nombres'=>'GONZALEZ OROZCO FREDY ERASTO',
                'celular'=>'4434089223','cargo'=>'ENCARGADO DE AGRUPAMIENTO','es_responsable'=>true,
                'crp'=>'24-6343','area_patrullaje'=>'BLINDAJE MORELIA',
                'observaciones'=>'LABORANDO | ARMA CORTA 24B220742 | ARMA LARGA 49323242 | DISPONIBLE 24 HRS',
                'activo'=>true,
            ],
            [
                'grado'=>'POLICIA','cuip'=>'AAGF820901H161137352','nombres'=>'ALCALA GONZALEZ FERNANDO',
                'celular'=>'4434400055','cargo'=>'TRIPULANTE','es_responsable'=>false,
                'crp'=>null,'area_patrullaje'=>null,
                'observaciones'=>'VACACIONES | ARMAS 24B219879 / LST002824 | DEL 19 AL 30 ENE 26 | REGRESA 02 FEB 26',
                'activo'=>true,
            ],
            [
                'grado'=>'POLICIA','cuip'=>'PENDIENTE','nombres'=>'AGUILAR VAZQUEZ JUAN MANUEL',
                'celular'=>null,'cargo'=>'TRIPULANTE','es_responsable'=>false,
                'crp'=>null,'area_patrullaje'=>null,
                'observaciones'=>'FRANCO | ARMAMENTO Y CUIP PENDIENTE',
                'activo'=>true,
            ],
            [
                'grado'=>'POLICIA','cuip'=>'AAAJ751202H16125841','nombres'=>'AVALOS AVALOS JUAN MANUEL',
                'celular'=>'4432394604','cargo'=>'TRIPULANTE','es_responsable'=>false,
                'crp'=>null,'area_patrullaje'=>null,
                'observaciones'=>'VACACIONES | ARMAS K84090Z / SDN290 | REGRESA 02 FEB 26',
                'activo'=>true,
            ],
            [
                'grado'=>'POLICIA','cuip'=>'BAGM901120H','nombres'=>'BARCENAS GONZALEZ MARCELINO',
                'celular'=>'4431676900','cargo'=>'ENCARGADO TURNO A','es_responsable'=>true,
                'crp'=>null,'area_patrullaje'=>null,
                'observaciones'=>'FRANCO | ARMAS 24B265469 / 991130153',
                'activo'=>true,
            ],
            [
                'grado'=>'POLICIA','cuip'=>'BERR850110M164798267','nombres'=>'BECERRA REYNOSO ROSALVA',
                'celular'=>'4436833486','cargo'=>'TRIPULANTE','es_responsable'=>false,
                'crp'=>null,'area_patrullaje'=>null,
                'observaciones'=>'FRANCO | ARMAS N76397Z / 21H003368',
                'activo'=>true,
            ],
            [
                'grado'=>'POLICIA','cuip'=>'CADG750304H16404384','nombres'=>'CALDERON DOMINGUEZ JOSE GUADALUPE',
                'celular'=>'4436823260','cargo'=>'TRIPULANTE','es_responsable'=>false,
                'crp'=>null,'area_patrullaje'=>null,
                'observaciones'=>'FRANCO | ARMAS P09949Z / 31102712',
                'activo'=>true,
            ],
            [
                'grado'=>'POL. 1°','cuip'=>'PENDIENTE','nombres'=>'DAVALOS ALVAREZ HUGO IVÁN',
                'celular'=>'4434920398','cargo'=>'TRIPULANTE','es_responsable'=>false,
                'crp'=>null,'area_patrullaje'=>null,
                'observaciones'=>'FRANCO | SIN ARMA',
                'activo'=>true,
            ],
            [
                'grado'=>'POL. 1°','cuip'=>null,'nombres'=>'JIMENEZ MORA CONSUELO',
                'celular'=>'4433717384','cargo'=>'TRIPULANTE','es_responsable'=>false,
                'crp'=>null,'area_patrullaje'=>null,
                'observaciones'=>'FRANCO | ARMAS 49211581 / 49322908',
                'activo'=>true,
            ],
            [
                'grado'=>'POLICIA','cuip'=>'JIVL720227H16142089','nombres'=>'JIMENEZ VALDEZ JOSE LUIS',
                'celular'=>'4431878640','cargo'=>'TRIPULANTE','es_responsable'=>false,
                'crp'=>null,'area_patrullaje'=>null,
                'observaciones'=>'FRANCO | ARMAS 24B221112 / 20K020295',
                'activo'=>true,
            ],
            [
                'grado'=>'POLICIA','cuip'=>'LOGM901114HMNPLR00','nombres'=>'LOPEZ GALLEGOS MARTIN ROBERTO',
                'celular'=>'4431014978','cargo'=>'TRIPULANTE','es_responsable'=>false,
                'crp'=>null,'area_patrullaje'=>null,
                'observaciones'=>'FRANCO | ARMAS 49211618 / 20L035563',
                'activo'=>true,
            ],
            [
                'grado'=>'POL: 2°','cuip'=>'OIBA721010H16143060','nombres'=>'ORTIZ BARCENAS ARTURO',
                'celular'=>'4433992619','cargo'=>'CUARTELERO','es_responsable'=>false,
                'crp'=>null,'area_patrullaje'=>null,
                'observaciones'=>'FRANCO | SIN ARMA',
                'activo'=>true,
            ],
            [
                'grado'=>'POLICIA','cuip'=>'PEML760104H16141993','nombres'=>'PEÑA MARTINEZ LUIS',
                'celular'=>'4434109744','cargo'=>'CUARTELERO','es_responsable'=>false,
                'crp'=>null,'area_patrullaje'=>null,
                'observaciones'=>'FRANCO | ARMAS 24B219160 / 20K013183',
                'activo'=>true,
            ],
            [
                'grado'=>'POLICIA','cuip'=>'REHJ781231H','nombres'=>'RENDON HERNANDEZ JESUS PEDRO',
                'celular'=>'4435512218','cargo'=>'ARRENDADOR','es_responsable'=>false,
                'crp'=>null,'area_patrullaje'=>null,
                'observaciones'=>'FRANCO | SIN ARMA',
                'activo'=>true,
            ],
            [
                'grado'=>'POLICIA','cuip'=>'VAGA771115H164649007','nombres'=>'VAZQUEZ GONZALEZ ALBERTO',
                'celular'=>'4431878818','cargo'=>'TRIPULANTE','es_responsable'=>false,
                'crp'=>null,'area_patrullaje'=>null,
                'observaciones'=>'FRANCO | ARMAS H07234Z / LGC023184',
                'activo'=>true,
            ],

            // ===================== TURNO B =====================

            [
                'grado'=>'POLICIA','cuip'=>'PENDIENTE','nombres'=>'ARROYO DURAN RICARDO JOSUE',
                'celular'=>null,'cargo'=>'TRIPULANTE','es_responsable'=>false,
                'crp'=>'19-6073','area_patrullaje'=>null,
                'observaciones'=>'LABORANDO | SERVICIOS VARIOS | ARMAMENTO PENDIENTE',
                'activo'=>true,
            ],
            [
                'grado'=>'POLICIA','cuip'=>'COSA000109H165206015','nombres'=>'CORONA SILVA ADRIAN',
                'celular'=>'4435766082','cargo'=>'TRIPULANTE','es_responsable'=>false,
                'crp'=>null,'area_patrullaje'=>'FILTROS VIEJOS',
                'observaciones'=>'LABORANDO | EQUINO | ARMAS 24B220710 / 20J015102 | REC. PREV. Y VIG.',
                'activo'=>true,
            ],
            [
                'grado'=>'POLICIA','cuip'=>'CAOV940811H165215496','nombres'=>'CHAVEZ OROZCO VICTOR DANIEL',
                'celular'=>'4437242426','cargo'=>'ENCARGADO TURNO B','es_responsable'=>true,
                'crp'=>'23-3454','area_patrullaje'=>'BLINDAJE MORELIA',
                'observaciones'=>'LABORANDO | ARMAS 24B221182 / 20L033792 | REC. PREV. Y VIG.',
                'activo'=>true,
            ],

            // ===================== EQUINOTERAPIA =====================

            [
                'grado'=>'POLICIA','cuip'=>'EIGV790605H164888565','nombres'=>'ESPINO GUTIEREZ VICTOR MANUEL',
                'celular'=>'4431847802','cargo'=>'ENCARGADO DE EQUINOTERAPIA','es_responsable'=>true,
                'crp'=>null,'area_patrullaje'=>null,
                'observaciones'=>'VACACIONES | DEL 19 AL 30 ENE 26 | REGRESA 02 FEB 26',
                'activo'=>true,
            ],
            [
                'grado'=>'POLICIA','cuip'=>'S/N','nombres'=>'GARIBAY CHAVEZ CARLOS FERNANDO',
                'celular'=>'4436106006','cargo'=>'EQUINOTERAPIA','es_responsable'=>false,
                'crp'=>null,'area_patrullaje'=>null,
                'observaciones'=>'FRANCO | SIN ARMA',
                'activo'=>true,
            ],
            [
                'grado'=>'POLICIA','cuip'=>'GOCS720222H16869684','nombres'=>'GOMORA CASTILLO SERAFIN',
                'celular'=>'4438585957','cargo'=>'EQUINOTERAPIA','es_responsable'=>false,
                'crp'=>null,'area_patrullaje'=>null,
                'observaciones'=>'FRANCO | SIN ARMA',
                'activo'=>true,
            ],
            [
                'grado'=>'POLICIA','cuip'=>'LESF890910M164737427','nombres'=>'LEON SAUCEDO FABIOLA ERENDIRA',
                'celular'=>'4433924506','cargo'=>'EQUINOTERAPIA','es_responsable'=>false,
                'crp'=>null,'area_patrullaje'=>null,
                'observaciones'=>'FRANCO | SIN ARMA',
                'activo'=>true,
            ],
            [
                'grado'=>'T.E.M.P.','cuip'=>'N/A','nombres'=>'OLIVARES VENEGAS HORTENSIA',
                'celular'=>'4434128536','cargo'=>'EQUINOTERAPIA','es_responsable'=>false,
                'crp'=>null,'area_patrullaje'=>null,
                'observaciones'=>'FRANCO | SIN ARMA',
                'activo'=>true,
            ],
            [
                'grado'=>'POLICIA','cuip'=>'PEMA750512H16219580','nombres'=>'PEREZ MUÑOZ ADOLFO',
                'celular'=>'4431680984','cargo'=>'EQUINOTERAPIA','es_responsable'=>false,
                'crp'=>null,'area_patrullaje'=>null,
                'observaciones'=>'FRANCO | SIN ARMA',
                'activo'=>true,
            ],
            [
                'grado'=>'POLICIA','cuip'=>'PIHG940928H164670024','nombres'=>'PIÑA HERNANDEZ GUILLERMO BRAYAN',
                'celular'=>'4434637993','cargo'=>'EQUINOTERAPIA','es_responsable'=>false,
                'crp'=>null,'area_patrullaje'=>null,
                'observaciones'=>'FRANCO | SIN ARMA',
                'activo'=>true,
            ],
            [
                'grado'=>'A.M.P','cuip'=>'N/A','nombres'=>'RANGEL MORA ELIEZER',
                'celular'=>'4433316898','cargo'=>'EQUINOTERAPIA','es_responsable'=>false,
                'crp'=>null,'area_patrullaje'=>null,
                'observaciones'=>'FRANCO | SIN ARMA',
                'activo'=>true,
            ],
            [
                'grado'=>'POLICIA','cuip'=>'VIGB760614H16197119','nombres'=>'VICTORIA GAMIÑO BENITO',
                'celular'=>'4433582581','cargo'=>'EQUINOTERAPIA','es_responsable'=>false,
                'crp'=>null,'area_patrullaje'=>null,
                'observaciones'=>'FRANCO | SIN ARMA',
                'activo'=>true,
            ],
            [
                'grado'=>'POLICIA','cuip'=>'ZAED770128H16671313','nombres'=>'ZAMUDIO ESTRADA JOSE DELFINO',
                'celular'=>'4433923292','cargo'=>'EQUINOTERAPIA','es_responsable'=>false,
                'crp'=>null,'area_patrullaje'=>null,
                'observaciones'=>'FRANCO | SIN ARMA',
                'activo'=>true,
            ],
            [
                'grado'=>'T.P.','cuip'=>'S/N','nombres'=>'ABONCE OCHOA CESAR ALFONSO',
                'celular'=>'4431815086','cargo'=>'AUXILIAR MEDICO VETERINARIO','es_responsable'=>false,
                'crp'=>null,'area_patrullaje'=>null,
                'observaciones'=>'FRANCO | SIN ARMA',
                'activo'=>true,
            ],
            [
                'grado'=>'POLICIA','cuip'=>'S/N','nombres'=>'CANALES CRUZ SERGIO',
                'celular'=>'4433301543','cargo'=>null,'es_responsable'=>false,
                'crp'=>null,'area_patrullaje'=>null,
                'observaciones'=>'FRANCO | ARMA CORTA N76412Z | ARMA LARGA 20L033808',
                'activo'=>true,
            ],
            [
                'grado'=>'POLICIA','cuip'=>'S/N','nombres'=>'CANO ZAVALA GUSTAVO',
                'celular'=>'4432438943','cargo'=>'MEDICO VETERINARIO','es_responsable'=>false,
                'crp'=>null,'area_patrullaje'=>null,
                'observaciones'=>'FRANCO | SIN ARMA',
                'activo'=>true,
            ],
            [
                'grado'=>'STASPE','cuip'=>'N/A','nombres'=>'CHAVEZ GONZALEZ ULISES OTHON',
                'celular'=>'4431999350','cargo'=>'CUARTELERO','es_responsable'=>false,
                'crp'=>null,'area_patrullaje'=>null,
                'observaciones'=>'FRANCO | SIN ARMA',
                'activo'=>true,
            ],
            [
                'grado'=>'POLICIA','cuip'=>'MECI720513MDFNMM02','nombres'=>'MENDEZ CAMARENA IMELDA',
                'celular'=>'4432867668','cargo'=>'CUARTELERO','es_responsable'=>false,
                'crp'=>null,'area_patrullaje'=>null,
                'observaciones'=>'VACACIONES | SIN ARMA | DEL 19 AL 30 ENE/26 | SE PRESENTA 02 FEB/26',
                'activo'=>true,
            ],
            [
                'grado'=>'T.E.M.P.','cuip'=>'N/A','nombres'=>'RENDON LEON JOSE ANGEL',
                'celular'=>'4431237167','cargo'=>'ARRENDADOR','es_responsable'=>false,
                'crp'=>null,'area_patrullaje'=>null,
                'observaciones'=>'FRANCO | SIN ARMA',
                'activo'=>true,
            ],
            [
                'grado'=>'POLICIA','cuip'=>'VALJ740626H094032530','nombres'=>'VAZQUEZ LOPEZ JUAN ANTONIO',
                'celular'=>'4431835491','cargo'=>'CUARTELERO','es_responsable'=>false,
                'crp'=>null,'area_patrullaje'=>null,
                'observaciones'=>'FRANCO | ARMA CORTA 24B220494 | ARMA LARGA 20K012538',
                'activo'=>true,
            ],
            [
                'grado'=>'POLICIA','cuip'=>'S/N','nombres'=>'YAHUACA SILVA JOSE MANUEL',
                'celular'=>'4434693050','cargo'=>'AUXILIAR MEDICO VETERINARIO','es_responsable'=>false,
                'crp'=>null,'area_patrullaje'=>null,
                'observaciones'=>'FRANCO | SIN ARMA',
                'activo'=>true,
            ],

            // ===================== OFICINA =====================

            [
                'grado'=>'POLICIA','cuip'=>'S/N','nombres'=>'CERVANTES LEON MARBELLA',
                'celular'=>'4434870879','cargo'=>'ENLACE ADMINISTRATIVO','es_responsable'=>false,
                'crp'=>null,'area_patrullaje'=>null,
                'observaciones'=>'FRANCO | OFICINA | SIN ARMA',
                'activo'=>true,
            ],
            [
                'grado'=>'POLICIA','cuip'=>'S/N','nombres'=>'CHAMU CIPRIANO JANET MONICA',
                'celular'=>'4432012346','cargo'=>'ENLACE ADMINISTRATIVO','es_responsable'=>false,
                'crp'=>null,'area_patrullaje'=>null,
                'observaciones'=>'FRANCO | OFICINA | SIN ARMA',
                'activo'=>true,
            ],
            [
                'grado'=>'POLICIA','cuip'=>'EIJR911216H','nombres'=>'ESPINO JAIMES RUBEN',
                'celular'=>'4439381792','cargo'=>'OFICINA','es_responsable'=>false,
                'crp'=>null,'area_patrullaje'=>null,
                'observaciones'=>'FRANCO | SIN ARMA',
                'activo'=>true,
            ],
            [
                'grado'=>'POLICIA','cuip'=>'S/N','nombres'=>'FRASCO VELAZQUEZ CITLALI ANDREA',
                'celular'=>'4433676510','cargo'=>'ENLACE SERMICH','es_responsable'=>false,
                'crp'=>null,'area_patrullaje'=>null,
                'observaciones'=>'VACACIONES | SIN ARMA | DEL 12 AL 23 ENE | SE PRESENTA 26 ENE',
                'activo'=>true,
            ],
            [
                'grado'=>'A.M.P','cuip'=>'S/N','nombres'=>'GARCIA JACOME JACQUELINE',
                'celular'=>'4433555706','cargo'=>'OFICINA','es_responsable'=>false,
                'crp'=>null,'area_patrullaje'=>null,
                'observaciones'=>'FRANCO | SIN ARMA',
                'activo'=>true,
            ],
            [
                'grado'=>'STASPE','cuip'=>'N/A','nombres'=>'LOPEZ MENDEZ MARIA ISABEL',
                'celular'=>'4433776109','cargo'=>'ENLACE COMBUSTIBLE','es_responsable'=>false,
                'crp'=>null,'area_patrullaje'=>null,
                'observaciones'=>'FRANCO | SIN ARMA',
                'activo'=>true,
            ],
            [
                'grado'=>'POLICIA','cuip'=>'S/N','nombres'=>'LOPEZ TORRES FERNANDA',
                'celular'=>'4431722096','cargo'=>'OFICINA','es_responsable'=>false,
                'crp'=>null,'area_patrullaje'=>null,
                'observaciones'=>'FRANCO | SIN ARMA',
                'activo'=>true,
            ],
            [
                'grado'=>'POLICIA','cuip'=>'MESB840714H','nombres'=>'MENDOZA SANTIAGO BENJAMIN',
                'celular'=>'4438626244','cargo'=>'ENLACE CALEA','es_responsable'=>false,
                'crp'=>null,'area_patrullaje'=>null,
                'observaciones'=>'FRANCO | SIN ARMA',
                'activo'=>true,
            ],

            // ===================== JORNADA ACUMULADA =====================

            [
                'grado'=>'POLICIA','cuip'=>'S/N','nombres'=>'CANALES LOPEZ JUAN CARLOS',
                'celular'=>'4432076221','cargo'=>'JORNADA ACUMULADA','es_responsable'=>false,
                'crp'=>null,'area_patrullaje'=>'INSTALACIONES',
                'observaciones'=>'LABORANDO | SIN ARMA',
                'activo'=>true,
            ],
            [
                'grado'=>'T.E.M.P.','cuip'=>'N/A','nombres'=>'CIPRIANO IXTA VICENTA',
                'celular'=>'4431327624','cargo'=>'JORNADA ACUMULADA','es_responsable'=>false,
                'crp'=>null,'area_patrullaje'=>'INSTALACIONES',
                'observaciones'=>'LABORANDO | SIN ARMA',
                'activo'=>true,
            ],
            [
                'grado'=>'POLICIA','cuip'=>'S/N','nombres'=>'CORTEZ ESPINOZA LUIS IVAN',
                'celular'=>'4431951651','cargo'=>'AUX. MED. VET./ JORNADA ACUMULADA','es_responsable'=>false,
                'crp'=>null,'area_patrullaje'=>'INSTALACIONES',
                'observaciones'=>'LABORANDO | SIN ARMA',
                'activo'=>true,
            ],
            [
                'grado'=>'STASPE','cuip'=>'N/A','nombres'=>'REYES BENITEZ HUMBERTO',
                'celular'=>'4432728355','cargo'=>'JORNADA ACUMULADA','es_responsable'=>false,
                'crp'=>null,'area_patrullaje'=>'INSTALACIONES',
                'observaciones'=>'LABORANDO | SIN ARMA',
                'activo'=>true,
            ],

            // ===================== LICENCIAS LABORALES =====================

            [
                'grado'=>'POLICIA','cuip'=>'GAAE840811M164796957','nombres'=>'GARCIA AYALA ERENDIRA',
                'celular'=>'4431983902','cargo'=>'EQUINOTERAPIA','es_responsable'=>false,
                'crp'=>null,'area_patrullaje'=>null,
                'observaciones'=>'LICENCIA LABORAL | SIN ARMA',
                'activo'=>true,
            ],
            [
                'grado'=>'STASPE','cuip'=>'N/A','nombres'=>'MARTINEZ CONTRERAS MARCELINO',
                'celular'=>'4431567856','cargo'=>'MEDICO VETERINARIO','es_responsable'=>false,
                'crp'=>null,'area_patrullaje'=>null,
                'observaciones'=>'LICENCIA LABORAL | SIN ARMA',
                'activo'=>true,
            ],

            // ===================== AREA CANINA =====================

            [
                'grado'=>'POL. 1°','cuip'=>'SEGR810114H091027530','nombres'=>'SEGUNDO GONZALEZ RODRIGO',
                'celular'=>'4434684521','cargo'=>'SEGUNDO ENCARGADO DEL AGRUPAMIENTO','es_responsable'=>true,
                'crp'=>'23-4265','area_patrullaje'=>'BLINDAJE MORELIA',
                'observaciones'=>'LABORANDO | ARMA CORTA 24B219062 | ARMA LARGA 43107163 | REC. PREV. Y VIG. | AREA CANINA',
                'activo'=>true,
            ],

            // -------- TURNO A (CANINA) --------

            [
                'grado'=>'POLICIA','cuip'=>'S/N','nombres'=>'ALCANTAR MARTINEZ ROSA ADRIANA',
                'celular'=>'4434770965','cargo'=>'APOYO A CUARTELERO','es_responsable'=>false,
                'crp'=>null,'area_patrullaje'=>null,
                'observaciones'=>'FRANCO | SIN ARMA | AREA CANINA | TURNO A',
                'activo'=>true,
            ],
            [
                'grado'=>'OFCIAL','cuip'=>null,'nombres'=>'CASTRO GARNICA JOSE ALFREDO',
                'celular'=>null,'cargo'=>'APOYO A CUARTELERO','es_responsable'=>false,
                'crp'=>null,'area_patrullaje'=>null,
                'observaciones'=>'FRANCO | ARMA CORTA 24B221275 | ARMA LARGA 43107154 | AREA CANINA | TURNO A | CELULAR PENDIENTE',
                'activo'=>true,
            ],
            [
                'grado'=>'POLICIA','cuip'=>'CAME890218H1642824782','nombres'=>'CHAVARRIA MEJIA JOSE ELIAS',
                'celular'=>'4431395159','cargo'=>'TRIPULANTE','es_responsable'=>false,
                'crp'=>null,'area_patrullaje'=>null,
                'observaciones'=>'FRANCO | ARMA CORTA K83901Z | ARMA LARGA LGC022969 | AREA CANINA | TURNO A',
                'activo'=>true,
            ],
            [
                'grado'=>'POLICIA','cuip'=>'S/N','nombres'=>'GARCIA JUAREZ ALISON GERALDINE',
                'celular'=>'4437942294','cargo'=>'APOYO A CUARTELERO','es_responsable'=>false,
                'crp'=>null,'area_patrullaje'=>null,
                'observaciones'=>'FRANCO | SIN ARMA | AREA CANINA | TURNO A',
                'activo'=>true,
            ],
            [
                'grado'=>'POL. 1°','cuip'=>'GUHJ771222H164360229','nombres'=>'GUERRERO HERREJON JESUS',
                'celular'=>'4431544622','cargo'=>'TRIPULANTE','es_responsable'=>false,
                'crp'=>null,'area_patrullaje'=>null,
                'observaciones'=>'FRANCO | ARMA CORTA P70976Z | ARMA LARGA 20K006734 | AREA CANINA | TURNO A',
                'activo'=>true,
            ],
            [
                'grado'=>'POLICIA','cuip'=>'MAHF820227H30667382','nombres'=>'MARTINEZ HERNANDEZ FRANCO',
                'celular'=>'4434216934','cargo'=>'ENC. DE TURNO "A"','es_responsable'=>true,
                'crp'=>null,'area_patrullaje'=>null,
                'observaciones'=>'FRANCO | ARMA CORTA N76506Z | ARMA LARGA LGC023076 | AREA CANINA | TURNO A',
                'activo'=>true,
            ],
            [
                'grado'=>'POLICIA','cuip'=>'FEHL770704H14222105','nombres'=>'FERRER HERNÁNDEZ LUCIO ENRIQUE',
                'celular'=>'4432172429','cargo'=>'TRIPULANTE','es_responsable'=>false,
                'crp'=>null,'area_patrullaje'=>null,
                'observaciones'=>'VACACIONES | ARMA CORTA 24B219856 | ARMA LARGA 21H003583 | DEL 19 AL 30 ENE 26 | REGRESA 02 FEB 26 | AREA CANINA | TURNO A',
                'activo'=>true,
            ],
            [
                'grado'=>'POLICIA','cuip'=>'S/N','nombres'=>'PADILLA ARROYO DANIEL',
                'celular'=>'4431413580','cargo'=>'APOYO A CUARTELERO','es_responsable'=>false,
                'crp'=>null,'area_patrullaje'=>null,
                'observaciones'=>'FRANCO | SIN ARMA | AREA CANINA | TURNO A',
                'activo'=>true,
            ],
            [
                'grado'=>'POLICIA','cuip'=>'RIVR960610H','nombres'=>'RICO VARGAS RAMON ADRIAN',
                'celular'=>'4431231378','cargo'=>'APOYO A CUARTELERO','es_responsable'=>false,
                'crp'=>null,'area_patrullaje'=>null,
                'observaciones'=>'FRANCO | SIN ARMA | AREA CANINA | TURNO A',
                'activo'=>true,
            ],
            [
                'grado'=>'POLICIA','cuip'=>'OIBF670910H16143065','nombres'=>'ORTIZ BENITEZ FILEMON',
                'celular'=>'4432266712','cargo'=>'TRIPULANTE','es_responsable'=>false,
                'crp'=>null,'area_patrullaje'=>null,
                'observaciones'=>'FRANCO | ARMA CORTA A394686 | ARMA LARGA LGC022982 | AREA CANINA | TURNO A',
                'activo'=>true,
            ],
            [
                'grado'=>'POLICIA','cuip'=>'VIRJ640531HDFLMS08','nombres'=>'VILLA RAMIREZ JESUS',
                'celular'=>'4431388369','cargo'=>'APOYO A CUARTELERO','es_responsable'=>false,
                'crp'=>null,'area_patrullaje'=>null,
                'observaciones'=>'FRANCO | ARMA CORTA K83204Z | ARMA LARGA LGC012472 | AREA CANINA | TURNO A',
                'activo'=>true,
            ],

            // -------- TURNO B (CANINA) --------

            [
                'grado'=>'POL.1','cuip'=>'BAVD851222H164836374','nombres'=>'BARRERA VILLEGAS DANIEL',
                'celular'=>'4437250392','cargo'=>'TRIPULANTE','es_responsable'=>false,
                'crp'=>'23-4265','area_patrullaje'=>'BLINDAJE MORELIA',
                'observaciones'=>'LABORANDO | ARMA CORTA 24B221148 | ARMA LARGA 20K000331 | REC. PREV. Y VIG. | AREA CANINA | TURNO B',
                'activo'=>true,
            ],
            [
                'grado'=>'POLICIA','cuip'=>'BUBJ730521H204742121','nombres'=>'BUSTAMANTE BONIFACIO JORGE LUIS',
                'celular'=>'4438705519','cargo'=>'APOYO A CUARTELERO','es_responsable'=>false,
                'crp'=>null,'area_patrullaje'=>'CUARTEL VALLADOLID',
                'observaciones'=>'LABORANDO | SIN ARMA | AREA CANINA | TURNO B',
                'activo'=>true,
            ],
            [
                'grado'=>'POLICIA','cuip'=>'PUFB690818M09651492','nombres'=>'DE LA PUENTE FIERROS BEATRIZ ATENA',
                'celular'=>null,'cargo'=>'APOYO A CUARTELERO','es_responsable'=>false,
                'crp'=>null,'area_patrullaje'=>'CUARTEL VALLADOLIOD',
                'observaciones'=>'LABORANDO | SIN ARMA | AREA CANINA | TURNO B | CELULAR PENDIENTE',
                'activo'=>true,
            ],
            [
                'grado'=>'POLICIA','cuip'=>'GOAM771216H16549323','nombres'=>'GOMEZ AYALA MOISES',
                'celular'=>'4432585294','cargo'=>'TRIPULANTE','es_responsable'=>false,
                'crp'=>'24-5740','area_patrullaje'=>'BLINDAJE MORELIA',
                'observaciones'=>'LABORANDO | ARMA CORTA P09508Z | ARMA LARGA LGC023205 | REC. PREV. Y VIG. | AREA CANINA | TURNO B',
                'activo'=>true,
            ],
            [
                'grado'=>'POLICIA','cuip'=>'S/N','nombres'=>'MERCADO PONCE VALDEMAR LEOPOLDO',
                'celular'=>'4432441809','cargo'=>'APOYO A CUARTELERO','es_responsable'=>false,
                'crp'=>null,'area_patrullaje'=>'CUARTEL VALLADOLIOD',
                'observaciones'=>'LABORANDO | SIN ARMA | AREA CANINA | TURNO B',
                'activo'=>true,
            ],
            [
                'grado'=>'POLICIA','cuip'=>'S/N','nombres'=>'REYES REYES FEDERICO',
                'celular'=>'4434837262','cargo'=>'APOYO A CUARTELERO','es_responsable'=>false,
                'crp'=>'24-5740','area_patrullaje'=>'BLINDAJE MORELIA',
                'observaciones'=>'LABORANDO | SIN ARMA | REC. PREV. Y VIG. | AREA CANINA | TURNO B',
                'activo'=>true,
            ],

            // -------- MIXTO (CANINA) --------

            [
                'grado'=>'POLICIA','cuip'=>'CAML880903H','nombres'=>'CALDERON MENDOZA LUIS',
                'celular'=>'4432725872','cargo'=>'AUXILIAR MEDICO VETERINARIO','es_responsable'=>false,
                'crp'=>null,'area_patrullaje'=>null,
                'observaciones'=>'FRANCO | SIN ARMA | AREA CANINA | MIXTO',
                'activo'=>true,
            ],
            [
                'grado'=>'POLICIA','cuip'=>'EIHJ851120H16115147','nombres'=>'ESPINOZA HERNANDEZ JESUS ANTONIO',
                'celular'=>'4432190582','cargo'=>'ENLACE CANINA','es_responsable'=>false,
                'crp'=>null,'area_patrullaje'=>null,
                'observaciones'=>'FRANCO | ARMA CORTA P71172Z | ARMA LARGA 31102469 | AREA CANINA | MIXTO',
                'activo'=>true,
            ],
            [
                'grado'=>'T.E.M.P.','cuip'=>'N/A','nombres'=>'FERNANDEZ YAÑEZ MARIA ASUNCION',
                'celular'=>'4436343971','cargo'=>'MEDICO VETERINARIO','es_responsable'=>false,
                'crp'=>null,'area_patrullaje'=>null,
                'observaciones'=>'FRANCO | SIN ARMA | AREA CANINA | MIXTO',
                'activo'=>true,
            ],
            [
                'grado'=>'POLICIA','cuip'=>'LOMM910115H','nombres'=>'LOPEZ MARTINEZ MANUEL ALBERTO',
                'celular'=>'8143247237','cargo'=>'AUXILIAR MEDICO VETERINARIO','es_responsable'=>false,
                'crp'=>null,'area_patrullaje'=>null,
                'observaciones'=>'VACACIONES | SIN ARMA | DEL 19 AL 30 ENE 26 | REGRESA 02 FEB 26 | AREA CANINA | MIXTO',
                'activo'=>true,
            ],
            [
                'grado'=>'POLICIA','cuip'=>'PECR911031H','nombres'=>'PEREZ CALDERON RAFAEL',
                'celular'=>'4431951923','cargo'=>'INSTRUCTOR CANINO','es_responsable'=>false,
                'crp'=>null,'area_patrullaje'=>null,
                'observaciones'=>'FRANCO | SIN ARMA | AREA CANINA | MIXTO',
                'activo'=>true,
            ],
            [
                'grado'=>'POLICIA','cuip'=>'REFA990227H','nombres'=>'REYES FERNANDEZ ADAL OMAR',
                'celular'=>null,'cargo'=>'INSTRUCTOR CANINO','es_responsable'=>false,
                'crp'=>null,'area_patrullaje'=>null,
                'observaciones'=>'FRANCO | SIN ARMA | AREA CANINA | MIXTO | CELULAR PENDIENTE',
                'activo'=>true,
            ],
            [
                'grado'=>'STASPE','cuip'=>'N/A','nombres'=>'REYES GARCIA JOSE MANUEL',
                'celular'=>'4432066451','cargo'=>'MVZ MED. VET. RESPONSABLE DE LOS CANINOS','es_responsable'=>true,
                'crp'=>null,'area_patrullaje'=>null,
                'observaciones'=>'VACACIONES | SIN ARMA | AREA CANINA | MIXTO',
                'activo'=>true,
            ],
            [
                'grado'=>'T.E.M.P.','cuip'=>'N/A','nombres'=>'RIOS GALLARDO ALBERTO VALENTINO',
                'celular'=>'5624898505','cargo'=>'CUARTELERO','es_responsable'=>false,
                'crp'=>null,'area_patrullaje'=>null,
                'observaciones'=>'FRANCO | SIN ARMA | AREA CANINA | MIXTO',
                'activo'=>true,
            ],
            [
                'grado'=>'POLICIA','cuip'=>'TAGV770506H16615875','nombres'=>'TAPIA GUILLEN JOSE VICTOR',
                'celular'=>'4437307536','cargo'=>'INSTRUCTOR CANINO','es_responsable'=>false,
                'crp'=>null,'area_patrullaje'=>null,
                'observaciones'=>'VACACIONES | ARMA CORTA 24B219417 | ARMA LARGA 21H003527 | DEL 19 AL 30 ENE 26 | REGRESA 02 FEB 26 | AREA CANINA | MIXTO',
                'activo'=>true,
            ],

            // -------- LICENCIAS LABORALES (CANINA) --------

            [
                'grado'=>'POL. 3°','cuip'=>'BAVA970311H164796948','nombres'=>'BAILON VEGA JOSE ANTONIO',
                'celular'=>'4432275959','cargo'=>'TRIPULANTE','es_responsable'=>false,
                'crp'=>null,'area_patrullaje'=>null,
                'observaciones'=>'LICENCIA LABORAL | SIN ARMA | AREA CANINA',
                'activo'=>true,
            ],
            [
                'grado'=>'POLICIA','cuip'=>'HEMJ800521H16173064','nombres'=>'HERNÁNDEZ MALDONADO JULIO',
                'celular'=>'4432277471','cargo'=>'TRIPULANTE','es_responsable'=>false,
                'crp'=>null,'area_patrullaje'=>null,
                'observaciones'=>'LICENCIA LABORAL | SIN ARMA | AREA CANINA',
                'activo'=>true,
            ],

        ];

        foreach ($data as $row) {
            Personal::create($row);
        }
    }
}
