
<?php

/ip firewall address-list

add address=192.168.17.168 comment=idUserNumber:380:Jairo list=morosos
add address=192.168.17.166 comment=idUserNumber:347:Monica list=morosos
add address=192.168.17.142 comment="idUserNumber:558:Patricia " list=morosos
add address=192.168.16.194 comment=idUserNumber:655:Diana list=morosos
add address=192.168.17.121 comment="idUserNumber:310:ESTADERO " list=morosos
add address=192.168.30.52 comment=idUserNumber:416:SEBASTIAN list=morosos
add address=192.168.30.232 comment="idUserNumber:405:Andrea " list=morosos
add address=192.168.16.140 comment=idUserNumber:439:Ever list=morosos
add address=192.168.30.210 comment="idUserNumber:503:Luis Eduardo" list=morosos
add address=192.168.17.85 comment="idUserNumber:697:Yuly Johana :Bello :Humadea:192.168.17.85:2021-06-10" list=morosos

add address=192.168.20.140 comment=\
    "idUserNumber:525:Daniel :Ramirez Higuera:Humadea km 7 Via San Martin Casa 2 :192.168.20.140:2021-07-27" list=morosos
add address=192.168.16.202 comment="idUserNumber:770:Jose Leonardo:Herrera:Veterinaria:192.168.16.202:2021-08-18" list=morosos
add address=192.168.40.20 comment="Semancafe 27 Agosto 2021" disabled=yes list=morosos
add address=192.168.16.145 comment=\
    "idUserNumber:718:Andrea :Zambrano :Verda monte cristo alto finca el porvenir :192.168.16.145:2021-09-21" list=morosos
add address=192.168.7.4 comment="idUserNumber:164:Ricardo Gonzales::HUMADEA VRDA :192.168.7.4:2021-10-01" list=morosos
add address=192.168.21.82 comment="idUserNumber:565:Nubia Ester:Ballestero:Cll 14 8-51:192.168.21.82:2021-10-01" list=morosos
add address=192.168.21.150 comment="idUserNumber:808:Luz Dary :Abril:Cra 9 # 13 - 38 :192.168.21.150:2021-10-01" list=morosos
add address=192.168.16.236 comment="idUserNumber:739:Erika:Torres:Fca Recreo Pio 12 T11:192.168.16.236:2021-11-02" list=morosos
add address=192.168.17.82 comment=\
    "idUserNumber:548:Leidy Niyered:Ayala Chaguendo:Fva Nueva Esperanza Ca\C3\B1o Grande Alto:192.168.17.82:2021-11-16" list=\
    morosos

add address=192.168.17.163 comment="idUserNumber:844:Merly:Mosquera:Fca Vergel Vra Montecristo:192.168.17.163:2021-12-01" list=\
    morosos

add address=192.168.20.222 comment=\
    "idUserNumber:751:Pedro Antonio:Rodriguez:Villa Milena Mza 5 Casa 4 Frente A Cancha:192.168.20.222:2021-12-15" list=morosos
add address=192.168.17.225 comment=\
    "idUserNumber:614:Yelitza Josefina:Gonzales:Fca El Placer Vrda La Paz:192.168.17.225:2022-01-03" list=morosos
add address=192.168.21.9 comment="771:Yenifer Katerine:Herrera:Dir:192.168.21.9:05-01-2022" list=morosos

add address=192.168.17.32 comment="idUserNumber:454:Ferney :Garcia:Escuela Vereda La Paz :192.168.17.32:2022-01-17" list=morosos

add address=192.168.17.3 comment="idUserNumber:888:Martha Lilia:Ninno:Fca Diamante Vrda San Agustin:192.168.17.3:2022-01-17" \
    list=morosos
add address=192.168.16.103 comment="idUserNumber:587:Silvestre:Lopez:Fca Rio Grande Cacayal:192.168.16.103:2022-01-28" list=\
    morosos
add address=192.168.16.220 comment="idUserNumber:733:Daniel :Sanchez :Vereda La isla finca el abuelo :192.168.16.220:2022-01-28" \
    list=morosos
add address=192.168.16.213 comment=idUserNumber:757:Olimpo:Cardenas:Direccion:192.168.16.213:2022-01-28 list=morosos
add address=192.168.25.37 comment="idUserNumber:394:Yenny:Luengas:Vrda Humadea:192.168.25.37:2022-02-15" list=morosos
add address=192.168.17.25 comment="idUserNumber:755:Tatiana:Aguilera:Brr Bambu:192.168.17.25:2022-02-15" list=morosos
add address=192.168.17.253 comment=idUserNumber:760:Edilson:Rianno:Dire:192.168.17.253:2022-02-15 list=morosos
add address=192.168.20.31 comment=\
    "idUserNumber:138:Marisol Diaz::FCA LLANERITA VRDA DANUBIO GUAMAL META:192.168.20.31:2022-03-08" list=morosos
add address=192.168.25.52 comment=\
    "idUserNumber:56:Ervin Mendez::VRDA CA\C3\91O GRANDE CASTILLA LA NUEVA:192.168.25.52:2022-03-15" list=morosos

add address=192.168.17.146 comment="idUserNumber:706:Maria Paula:Mejia:Vrda Encanto- Ariza:192.168.17.146:2022-03-31" list=\
    morosos
add address=192.168.17.187 comment="idUserNumber:942:Luis Alirio:Castellanos:Vrda Encanto Colgas:192.168.17.187:2022-03-31" list=\
    morosos





    /queue simple
    add limit-at=0/5500k max-limit=0/5500k name="Danilo San Antonio" target=192.168.20.9/32
    add limit-at=0/6M max-limit=0/6M name="Jose David Ria\F1o s Pedro" target=192.168.17.230/32
    add limit-at=0/11M max-limit=0/11M name="Natal\ED Col\F3n" target=192.168.16.76/32
    add limit-at=0/5M max-limit=0/5M name=Conmilenio target=192.168.21.23/32
    add limit-at=4M/4M max-limit=4M/4M name="David San isidro" target=192.168.21.178/32
    add limit-at=4M/4M max-limit=4M/4M name="David  San Isidro" target=192.168.21.30/32
    add limit-at=0/10M max-limit=0/10M name="Jesus Wenet Montecristo" target=192.168.17.153/32
    add limit-at=0/7500k max-limit=0/7500k name="Karen lorena negrete benitez      (  Frente a mama de Ervin)" target=\
        192.168.17.156/32
    add limit-at=0/7M max-limit=0/7M name="Silvestre Lopez Cacayal" target=192.168.16.218/32
    add disabled=yes limit-at=0/20M max-limit=0/20M name="Hilda Urrego" target=192.168.17.41/32
    add limit-at=0/5M max-limit=0/5M name="Diana Loboa" target=192.168.16.184/32
    add limit-at=0/10M max-limit=0/10M name="Derly Catherine Agudelo" target=192.168.16.248/32
    add limit-at=0/18M max-limit=0/18M name="Repetidor violetas alpie Chileno" target=192.168.16.233/32
    add limit-at=0/4M max-limit=0/4M name="Martha Judith castillo" target=192.168.17.22/32
    add limit-at=0/4500k max-limit=0/4500k name="Augusto Carrillo" target=192.168.16.230/32
    add limit-at=0/4300k max-limit=0/4300k name="Marisol Diaz" target=192.168.20.31/32
    add limit-at=3M/9M max-limit=3M/9M name="Derivacion San Lorenzo Agrolito" target=192.168.16.189/32
    add limit-at=0/20M max-limit=0/20M name="San Lorenzo II" target=192.168.17.70/32
    add limit-at=0/5M max-limit=0/5M name="Yenny Mora" target=192.168.21.18/32
    add limit-at=0/8M max-limit=0/8M name="Los del switch tp-link" target=192.168.21.83/32
    add limit-at=0/4384k max-limit=0/4384k name="Juan Pablo Madrigal" target=192.168.17.167/32
    add limit-at=0/8M max-limit=0/8M name=Servipetroleos target=192.168.21.11/32
    add limit-at=0/6M max-limit=0/6M name="Ramiro Rozo" target=192.168.17.249/32
    add limit-at=0/4M max-limit=0/4M name=Interrapidisimo target=192.168.21.8/32
    add limit-at=0/4M max-limit=0/4M name=Expominerales target=192.168.40.28/32
    add limit-at=0/6M max-limit=0/6M name="Andrea  agrolirto San Lorenzo" target=192.168.16.189/32
    add limit-at=0/5M max-limit=0/5M name="Marlen Aguilera" target=192.168.17.51/32
    add limit-at=0/3M max-limit=0/3M name="Pedro Antonio San Lorenzo" target=192.168.20.222/32
    add limit-at=0/11M max-limit=0/11M name="Reina Victoria" target=192.168.16.90/32
    add limit-at=0/7M max-limit=0/7M name="Repetidor Javier Pardo" target=192.168.16.244/32
    add limit-at=0/7M max-limit=0/7M name="Repetidor Sabanas - Alexander" target=192.168.16.247/32
    add limit-at=0/5M max-limit=0/5M name="Profe Nora" target=192.168.21.144/32
    add limit-at=0/6M max-limit=0/6M name="Wilmenr Sun Rise" target=192.168.16.186/32
    add limit-at=0/9M max-limit=0/9M name="Repetridor Yulieth" target=192.168.17.152/32
    add limit-at=0/15M max-limit=0/15M name=Alcaravan target=192.168.17.13/32
    add limit-at=0/3M max-limit=0/3M name="Los Cocos" target=192.168.17.166/32
    add limit-at=0/3M max-limit=0/3M name="Leo Servientrega" target=192.168.17.180/32
    add limit-at=0/4M max-limit=0/4M name="Weimar el Carmen" target=192.168.21.120/32
    add limit-at=0/6M max-limit=0/6M name="Repetidor Opitas" target=192.168.17.33/32
    add limit-at=0/8M max-limit=0/8M name="Repetidor  Rebeca San Lorenzo" target=192.168.16.15/32
    add limit-at=0/6M max-limit=0/6M name="Mirian Nidia Mora 2 clientes" target=192.168.16.40/32
    add limit-at=0/4M max-limit=0/4M name="Rudimar andreina mojica" target=192.168.17.209/32
    add limit-at=0/10M max-limit=0/10M name="Martha Beltran" target=192.168.17.113/32
    add limit-at=0/5800k max-limit=0/5800k name="Julian Camilo Torres" target=192.168.16.216/32
    add limit-at=0/10M max-limit=0/10M name="Repetidor San Agust\EDn 3" target=192.168.17.6/32
    add limit-at=0/9M max-limit=0/9M name="Repetidor Munar" target=192.168.17.199/32
    add max-limit=0/6M name="Flor Castillo" target=192.168.16.188/32
    add limit-at=0/18M max-limit=0/18M name="Repetidor Coste\F1os" target=192.168.17.29/32
    add limit-at=0/7M max-limit=0/7M name="Esteban Tellez" target=192.168.17.220/32
    add limit-at=0/8M max-limit=0/8M name="Miguel Hern\E1ndez" target=192.168.16.179/32
    add limit-at=0/10M max-limit=0/10M name="Daniel Cifuentes" target=192.168.16.237/32
    add limit-at=0/6M max-limit=0/6M name=Edilberto target=192.168.16.144/32
    add limit-at=0/6M max-limit=0/6M name="Alejandro Gutierrez" target=192.168.16.225/32
    add limit-at=0/5200k max-limit=0/5200k name="Alexander Violetas" target=192.168.17.66/32
    add limit-at=0/5M max-limit=0/5M name="Jimmi Cortez Potrerillo" target=192.168.17.165/32
    add limit-at=0/5M max-limit=0/5M name="Mina La Isla" target=192.168.17.144/32
    add limit-at=0/6M max-limit=0/6M name="Erika Garay" target=192.168.17.151/32
    add limit-at=0/18M max-limit=0/18M name="Repetidor Rigo-Gustavo" target=192.168.21.27/32
    add limit-at=0/7M max-limit=0/7M name="Yenny Arias Pardo" target=192.168.17.161/32
    add limit-at=0/7M max-limit=0/7M name="Repetidor Prefabricada trivi\F1o m Bajo" target=192.168.17.159/32
    add limit-at=0/6M max-limit=0/6M name="Policarpo Ramirez" target=192.168.16.227/32
    add limit-at=0/6M max-limit=0/6M name="Ricardo Gamboa" target=192.168.16.228/32
    add limit-at=0/3M max-limit=0/3M name="H\E9ctor Florez Aguires" target=192.168.16.203/32
    add limit-at=0/6M max-limit=0/6M name="Ricardo Gil Hotel" target=192.168.16.223/32
    add limit-at=0/6500k max-limit=0/6500k name="lucy rubiano" target=192.168.16.235/32 time=0s-1d,sun,mon,tue,wed,thu,fri,sat
    add limit-at=0/4M max-limit=0/4M name="Carlos Garc\EDa alpie Hermes" target=192.168.16.196/32
    add limit-at=0/9M max-limit=0/9M name="Repetidor San Agustin 2 Kiosko" target=192.168.16.214/32
    add limit-at=0/6M max-limit=0/6M name="Repetidor Deisy Urrego" target=192.168.16.193/32
    add limit-at=0/5M max-limit=0/5M name="Claudia Vrda Isla Techo Azul" target=192.168.16.252/32
    add limit-at=4M/4M max-limit=4M/4M name="Carlos Arturo tirado (w)" target=192.168.21.198/32
    add limit-at=4M/4M max-limit=4M/4M name="Ronal Trocha 5 (w)" target=192.168.16.154/32
    add name="Todos Los Clientes" queue=Upload/Download target=""
    


?>  