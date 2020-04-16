<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CinNumberController extends Controller
{

    protected $cities_code = [
    "French_Legation" =>	"11114",
    "Sidist-Kilo_1" =>	"11122",
    "Sidist-Kilo_2" =>	"11123",
    "Sidist-Kilo_3" =>	"11124",
    "Addisu-Gebeya" =>	"11127",
    "Kuyu" =>	"11131",
    "Alem-Ketema" =>	"11132",
    "Deber-Tsige" =>	"11133",
    "Muke-Turi" =>	"11134",
    "Fitche" =>	"11135",
    "Arada_1" => "11111",
    "Arada_2" =>	"11112",
    "Arada_3" =>	"11155",
    "Arada_4" =>	"11156",
    "Arada_5" =>	"11157",
    "Arada_6" =>	"11158",
    "Sululta" =>	"11186",
    "Goha-Tsion" =>	"11187",
    "Chancho" =>	"11188",
    "Hagere-Hiwot" =>	"11236",
    "Holeta-Gent" => "11237",
    "Jeldu" =>	"11238",
    "Ginchi" =>	"11258",
    "Shegole" =>	"11259",
    "Asko" =>	"11270",
    "Addis-Ketema_1" =>	"11213",
    "Addis-Ketema_2" =>	"11275",
    "Addis-Ketema_3" =>	"11276",
    "Addis-Ketema_4" =>	"11277",
    "Addis-Ketema_6" =>	"11278",
    "Kolfe"	=> "11279",
    "Guder"	=> "11282",
    "Addis-Alem"	=> "11283",
    "Burayu"	=> "11284",
    "Wolenkomi"	=> "11285",
    "Enchini"	=> "11286",
    "Old-Airport_1"	=> "11320",
    "Old-Airport_2"	=> "11371",
    "Old-Airport_3"	=> "11372",
    "Old-Airport_4"	=> "11373",
    "Old-Airport_5"	=> "11374",
    "Mekanisa"	=> "11321",
    "Wolkite"	=> "11330",
    "Endibir"	=> "11331",
    "Gunchire"	=> "11332",
    "Sebeta"	=> "11338",
    "Teji"	=> "11339",
    "Ghion"	=> "11341",
    "Tullu-Bollo"	=> "11342",
    "Jimma"	=> "11348",
    "Ayer-Tena"	=> "11348",
    "Keranyo"	=> "11349",
    "Alem-Gena"	=> "11387",
    "Keira_1"	=> "11416",
    "Keira_2"	=> "11465",
    "Keira_3"	=> "11466",
    "Keira_4"	=> "11467",
    "Keira_5"	=> "11468",
    "Hana-Mariam"	=> "11419",
    "Dukem"	=> "11432",
    "Debre-Zeit"	=> "11433",
    "Akaki"	=> "11434",
    "Kaliti"	=> "11439",
    "Nifas-Silk_1"	=> "11442",
    "Nifas-Silk_2"	=> "11443",
    "Nifas-Silk_3"	=> "11440",
    "Filwoha_2"	=> "11515",
    "Filwoha_3"	=> "11551",
    "Filwoha_4"	=> "11550",
    "Filwoha_5"	=> "11553",
    "Filwoha_6"	=> "11552",
    "Filwoha_7"	=> "11554",
    "Bole-Michael"	=> "11626",
    "Gerji"	=> "11629",
    "Yeka_1"	=> "11645",
    "Yeka_2"	=> "11646",
    "Kotebe"	=> "11660",
    "Bole_1"	=> "11618",
    "Bole_2"	=> "11661",
    "Bole_3"	=> "11662",
    "Bole_4"	=> "11663",
    "Bole_5"	=> "11664",
    "Bole_6"	=> "11669",
    "Civil-Aviation"	=> "11665",
    "Debre-Sina"	=> "11680",
    "Debre-Birehan"	=> "11681",
    "Mehal-Meda"	=> "11685",
    "Sendafa"	=> "11686",
    "Sheno"	=> "11687",
    "Enwari"	=> "11688",


    // South-East Region[edit]
    // LIST OF AREA CODES
    // Area name	Code and number
    "Nazreth_1"	=> "22111",
    "Nazreth_2"	=> "22112",
    "Wolenchiti"	=> "22113",
    "Melkawarer"	=> "22114",
    "Alem Tena"	=> "22115",
    "Modjo"	=> "22116",
    "Meki"	=> "22118",
    "Wonji"	=> "22220",
    "Shoa"	=> "22221",
    "Arerti"	=> "22223",
    "Awash"	=> "22224",
    "Melkasa"	=> "22225",
    "Metehara"	=> "22226",
    "Agarfa"	=> "22227",
    "Sire"	=> "22330",
    "Asela"	=> "22331",
    "Bokoji"	=> "22332",
    "Dera"	=> "22333",
    "Huruta"	=> "22334",
    "Iteya"	=> "22335",
    "Assasa"	=> "22336",
    "Kersa"	=> "22337",
    "Sagure"	=> "22338",
    "Diksis"	=> "22339",
    "Abomsa"	=> "22441",
    "Ticho"	=> "22444",
    "Gobesa"	=> "22446",
    "Goro"	=> "22447",
    "Bale-Goba"	=> "22661",
    "Gessera"	=> "22662",
    "Adaba"	=> "22663",
    "Ghinir"	=> "22664",
    "Robe"	=> "22665",
    "Dodolla"	=> "22666",
    "Dolomena"	=> "22668",



    // "North-East Region[edit]
    // "LIST OF AREA CODES
    // "Area name	Code and number
    "Kabe"	=> "33110",
    "Dessie"	=> "33111",
    "Dessie_2"	=> "33112",
    "Kobo-Robit"	=> "33113",
    "Akesta"	=> "33114",
    "Woreilu"	=> "33116",
    "Tenta"	=> "33117",
    "Senbete"	=> "33118",
    "Mekana-Selam"	=> "33220",
    "Bistima"	=> "33221",
    "Hayk"	=> "33222",
    "Mille"	=> "33223",
    "Wuchale"	=> "33224",
    "Elidar"	=> "33225",
    "Jama"	=> "33226",
    "Sirinka"	=> "33330",
    "Woldia"	=> "33331",
    "Mersa"	=> "33333",
    "Kobo"	=> "33334",
    "Lalibela"	=> "33336",
    "Bure"	=> "33338",
    "Manda"	=> "33339",
    "Sekota"	=> "33440",
    "Ansokia"	=> "33444",
    "Logia"	=> "33550",
    "Kombolcha"	=> "33551",
    "Harbu"	=> "33552",
    "Bati"	=> "33553",
    "Kemise"	=> "33554",
    "Assayta"	=> "33555",
    "Dupti"	=> "33556",
    "Majate"	=> "33660",
    "Epheson"	=> "33661",
    "Shoa Robit"	=> "33664",
    "Semera"	=> "33666",
    "Decheotto"	=> "33667",



    // "North Region[edit]
    // "LIST OF AREA CODES
    // "Area name	Code and number
    "Mekelle"	=> "34440",
    "Mekele_2"	=> "34441",
    "Quiha" => "34442",
    "Wukro"	=> "34443",
    "Shire-Endasselassie"	=> "34444",
    "Adigrat"	=> "34445",
    "Abi-Adi"	=> "34446",
    "Senkata"	=> "34447",
    "Humera"	=> "34448",
    "Shiraro"	=> "34550",
    "Korem"	=> "34551",
    "Betemariam"	=> "34552",
    "A.Selam"	=> "34554",
    "Rama"	=> "34555",
    "Adi Daero"	=> "34556",
    "Adi Gudem"	=> "34660",
    "Endabaguna"	=> "34661",
    "Mai-Tebri"	=> "34662",
    "Waja"	=> "34663",
    "Adwa"	=> "34771",
    "Inticho"	=> "34772",
    "Edaga-Hamus"	=> "34773",
    "Alemata"	=> "34774",
    "Axum"	=> "34775",

    // "Eastern Region[edit]
    // "LIST OF AREA CODES
    // "Area name	Code and number
    "DireDawa"	=> "25111",
    "Dire Dawa_2"	=> "25112",
    "Shinile"	=> "25114",
    "Artshek"	=> "25115",
    "Melka-Jeldu"	=> "25116",
    "Bedeno"	=> "25332",
    "Deder"	=> "25333",
    "Grawa"	=> "25334",
    "Chelenko"	=> "25335",
    "Kersa"	=> "25336",
    "Kobo"	=> "25337",
    "Kombolocha"	=> "25338",
    "Hirna"	=> "25441",
    "Miesso"	=> "25444",
    "Erer"	=> "25446",
    "Hurso"	=> "25447",
    "Asebe-Teferi"	=> "25551",
    "Assebot"	=> "25554",
    "Alemaya"	=> "25661",
    "Aweday"	=> "25662",
    "Babile"	=> "25665",
    "Harar I"	=> "25666",
    "Harar II"	=> "25667",
    "Kebribeyah"	=> "25669",
    "Degahabur"	=> "25771",
    "Gursum"	=> "25772",
    "Kabri-Dehar"	=> "25774",
    "Jigiga"	=> "25775",
    "Godie"	=> "25776",
    "Teferi-Ber"	=> "25777",
    "Chinagson"	=> "25779",
    "Kelafo"	=> "25880",



    // South Region[edit]
    // LIST OF AREA CODES
    // Area name	Code and number
    "Shashamane"	=> "46110",
    "Shashamane_2"	=> "46111",
    "Kofele"	=> "46112",
    "Wondo-Kela"	=> "46114",
    "Butajira"	=> "46115",
    "Arsi-Negele"	=> "46116",
    "Adame-Tulu"	=> "46117",
    "Kuyera"	=> "46118",
    "WLL-Shasemene"	=> "46119",
    "Awassa_1"	=> "46220",
    "Awassa_2"	=> "46221",
    "Wonda-Basha"	=> "46222",
    "Aleta-Wondo"	=> "46224",
    "Yirgalem"	=> "46225",
    "Leku"	=> "46226",
    "Chuko"	=> "46227",
    "Dilla"	=> "46331",
    "Yirga-Chefe"	=> "46332",
    "Wonago"	=> "46333",
    "Shakiso"	=> "46334",
    "Kibre-Mengist"	=> "46335",
    "Ziway"	=> "46441",
    "Hagere-Mariam"	=> "46443",
    "Moyale"	=> "46444",
    "Negele-Borena"	=> "46445",
    "Yabello"	=> "46446",
    "Dolo Odo"	=> "46449",
    "Wollayta"	=> "46551",
    "Durame"	=> "46554",
    "Hossena"	=> "46555",
    "Alaba-Kulito"	=> "46556",
    "Enseno"	=> "46558",
    "Boditi"	=> "46559",
    "Kebado"	=> "46660",
    "Werabe"	=> "46771",
    "Gidole"	=> "46774",
    "Sawla"	=> "46777",
    "ArbaMinch"	=> "46881",
    "Kibet"	=> "46882",
    "Buii"	=> "46883",
    "Arbaminch-WLL"	=> "46881",



    // "South Western Region[edit]
    // "LIST OF AREA CODES
    // "Area name	Code and number
    "Jimma"	=> "47111",
    "Jimma _2"	=> "47112",
    "Serbo"	=> "47113",
    "Assendabo"	=> "47114",
    "Omonada"	=> "47115",
    "Seka"	=> "47116",
    "Sekoru"	=> "47117",
    "Shebe"	=> "47118",
    "WLL-Jimma"	=> "47119",
    "Agaro"	=> "47221",
    "Ghembo"	=> "47222",
    "Dedo"	=> "47223",
    "Limmu-Genet"	=> "47224",
    "Haro"	=> "47225",
    "Yebu"	=> "47226",
    "Atnago"	=> "47228",
    "Ghembe"	=> "47229",
    "Bonga"	=> "47331",
    "Yayo"	=> "47333",
    "Maji"	=> "47334",
    "Mizan-Teferi"	=> "47335",
    "Aman"	=> "47336",
    "Chora"	=> "47337",
    "Metu"	=> "47441",
    "Dembi"	=> "47443",
    "Darimu"	=> "47444",
    "Bedele"	=> "47445",
    "Hurumu"	=> "47446",
    "Gambela"	=> "47551",
    "Itang"	=> "47552",
    "Jikawo"	=> "47553",
    "Gore"	=> "47554",
    "Tepi"	=> "47556",
    "Macha"	=> "47558",
    "Abebo"	=> "47559",



    // Western Region[edit]
    // LIST OF AREA CODES
    // Area name	Code and number
    "Ghedo" => "5227",
    "Ejaji"	=> "57550",
    "Dembidolo"	=> "57555",
    "Nekemte"	=> "57661",
    "Fincha"	=> "57664",
    "Backo"	=> "57665",
    "Shambu"	=> "57666",
    "Arjo"	=> "57667",
    "Sire"	=> "57668",
    "Ghimbi"	=> "57771",
    "Nedjo"	=> "57774",
    "Assosa"	=> "57775",
    "Mendi"	=> "57776",
    "Billa"	=> "57777",
    "Guliso"	=> "57778",



    // North Western Region[edit]
    // LIST OF AREA CODES
    // Area name	Code and number
    "Gonder"	=> "58111",
    "Azezo"	=> "58114",
    "Gilgel-Beles"	=> "58119",
    "Bahir dar"	=> "58220",
    "Bahir-Dar_2"	=> "58226",
    "Dangla"	=> "58221",
    "Durbette" => "58223",
    "Abcheklite"	=> "58223",
    "Gimjabet-Mariam"	=> "58224",
    "Chagni" => "58225",
    "Metekel"	=> "58225",
    "Enjibara Kosober"	=> "58227",
    "Tilili"	=> "58229",
    "Merawi"	=> "58330",
    "Metema"	=> "58331",
    "Maksegnit"	=> "58332",
    "Chilga"	=> "58333",
    "Chewahit"	=> "58334",
    "Kola-deba"	=> "58335",
    "Delgi"	=> "58336",
    "Adet"	=> "58338",
    "Ebinat"	=> "58440",
    "Debre-Tabour"	=> "58441",
    "Hamusit"	=> "58443",
    "Addis-Zemen"	=> "58444",
    "Nefas-Mewcha"	=> "58445",
    "Worota"	=> "58446",
    "Mekane-Eyesus"	=> "58447",
    "Teda"	=> "58448",
    "Pawe"	=> "58550",
    "Motta"	=> "58661",
    "Keraniyo"	=> "58662",
    "Debre-Work"	=> "58663",
    "Gunde-Woin"	=> "58664",
    "Bichena"	=> "58665",
    "Mankusa"	=> "58770",
    "Debre-Markos_1"	=> "58771",
    "Debre-Markos_2"	=> "58778",
    "Lumame"	=> "58772",
    "Denbecha"	=> "58773",
    "Bure"	=> "58774",
    "Finote-Selam"	=> "58775",
    "Dejen"	=> "58776",
    "Amanuel"	=> "58777",
    "Jiga"	=> "58779",
    "Dubti" => "58780",
    "Adama" => "58781",
    "Debre Zeyte" => "58782",
    "Assela" => "58783",
    "Debre brhan" => "58784",
    "Hawassa" => "58785",
    "Sodo" => "58786",
    "Jijga" => "58787",
    "Harar" => "58788",
    "Addis ketama" => "58789",
    "Akaki qality" => "58790",
    "Arada" => "58791",
    "Bole" => "58792",
    "Gulele" => "58793",
    "Qirqos" => "58794",
    "Kolfe qeranio" => "58795",
    "Ldeta" => "58796",
    "Nfasslklafto" => "58797",
    "Yeka" => "58798",
    ];

    public function __construct($city){
      $this->cin_init = $this->cities_code[$city];//get the city from the area code
      echo $this->cin_init . '  his <br> ';

      $this->shuffler_code = $this->get_shuffler_code();//generete and get shufller randomly
      echo $this->shuffler_code . '  his <br> ';

      $this->cin_to_append = $this->get_cin_to_append();

      $this->cin = $this->get_final_cin($this->cin_init, $this->cin_to_append, $this->shuffler_code);
    }

    public function get_shuffler_code(){
      return rand(0,9);
    }

    public function get_cin_to_append(){
      $append = "";
      for($i=0;$i<6;$i++){
        $append .= rand(0,9);
      }
      return str_shuffle($append);
    }

    public function get_final_cin($area_code, $append, $shuffle){
      $cin = $area_code.$append.$shuffle;
      return $cin;
    }



    public function cin_getter(){
      return $this->cin; 
    }

    public function cin_encoder($cin){
      $len = strlen($cin);
      $encoder = $cin[$len-1];
      $encoded_cin = $cin;
      switch ($encoder) {
        case '0':
          $encoded_cin[0] = $cin[7];
          $encoded_cin[1] = $cin[0];
          $encoded_cin[2] = $cin[5];
          $encoded_cin[3] = $cin[10];
          $encoded_cin[4] = $cin[8];
          $encoded_cin[5] = $cin[4];
          $encoded_cin[6] = $cin[3];
          $encoded_cin[7] = $cin[1];
          $encoded_cin[8] = $cin[9];
          $encoded_cin[9] = $cin[2];
          $encoded_cin[10] = $cin[6];
          $encoded_cin[11] = $cin[11];
          break;
        case '1':
          $encoded_cin[0] = $cin[1];
          $encoded_cin[1] = $cin[8];
          $encoded_cin[2] = $cin[2];
          $encoded_cin[3] = $cin[6];
          $encoded_cin[4] = $cin[0];
          $encoded_cin[5] = $cin[3];
          $encoded_cin[6] = $cin[5];
          $encoded_cin[7] = $cin[10];
          $encoded_cin[8] = $cin[7];
          $encoded_cin[9] = $cin[4];
          $encoded_cin[10] = $cin[9];
          $encoded_cin[11] = $cin[11];
          break;
        case '2':
          $encoded_cin[0] = $cin[10];
          $encoded_cin[1] = $cin[2];
          $encoded_cin[2] = $cin[4];
          $encoded_cin[3] = $cin[8];
          $encoded_cin[4] = $cin[3];
          $encoded_cin[5] = $cin[5];
          $encoded_cin[6] = $cin[7];
          $encoded_cin[7] = $cin[9];
          $encoded_cin[8] = $cin[0];
          $encoded_cin[9] = $cin[6];
          $encoded_cin[10] = $cin[1];
          $encoded_cin[11] = $cin[11];
          break;
        case '3':
          $encoded_cin[0] = $cin[5];
          $encoded_cin[1] = $cin[9];
          $encoded_cin[2] = $cin[1];
          $encoded_cin[3] = $cin[4];
          $encoded_cin[4] = $cin[10];
          $encoded_cin[5] = $cin[3];
          $encoded_cin[6] = $cin[0];
          $encoded_cin[7] = $cin[2];
          $encoded_cin[8] = $cin[6];
          $encoded_cin[9] = $cin[7];
          $encoded_cin[10] = $cin[8];
          $encoded_cin[11] = $cin[11];
          break;
        case '4':
          $encoded_cin[0] = $cin[8];
          $encoded_cin[1] = $cin[3];
          $encoded_cin[2] = $cin[7];
          $encoded_cin[3] = $cin[0];
          $encoded_cin[4] = $cin[9];
          $encoded_cin[5] = $cin[2];
          $encoded_cin[6] = $cin[4];
          $encoded_cin[7] = $cin[6];
          $encoded_cin[8] = $cin[1];
          $encoded_cin[9] = $cin[5];
          $encoded_cin[10] = $cin[10];
          $encoded_cin[11] = $cin[11];
          break;
        case '5':
          $encoded_cin[0] = $cin[7];
          $encoded_cin[1] = $cin[0];
          $encoded_cin[2] = $cin[5];
          $encoded_cin[3] = $cin[10];
          $encoded_cin[4] = $cin[8];
          $encoded_cin[5] = $cin[4];
          $encoded_cin[6] = $cin[3];
          $encoded_cin[7] = $cin[1];
          $encoded_cin[8] = $cin[9];
          $encoded_cin[9] = $cin[2];
          $encoded_cin[10] = $cin[6];
          $encoded_cin[11] = $cin[11];
          break;
        case '6':
          $encoded_cin[0] = $cin[7];
          $encoded_cin[1] = $cin[0];
          $encoded_cin[2] = $cin[5];
          $encoded_cin[3] = $cin[10];
          $encoded_cin[4] = $cin[8];
          $encoded_cin[5] = $cin[4];
          $encoded_cin[6] = $cin[3];
          $encoded_cin[7] = $cin[1];
          $encoded_cin[8] = $cin[9];
          $encoded_cin[9] = $cin[2];
          $encoded_cin[10] = $cin[8];
          $encoded_cin[11] = $cin[11];
          break;
        case '7':
          $encoded_cin[0] = $cin[7];
          $encoded_cin[1] = $cin[0];
          $encoded_cin[2] = $cin[5];
          $encoded_cin[3] = $cin[10];
          $encoded_cin[4] = $cin[8];
          $encoded_cin[5] = $cin[4];
          $encoded_cin[6] = $cin[3];
          $encoded_cin[7] = $cin[1];
          $encoded_cin[8] = $cin[9];
          $encoded_cin[9] = $cin[2];
          $encoded_cin[10] = $cin[6];
          $encoded_cin[11] = $cin[11];
          break;
        case '8':
          $encoded_cin[0] = $cin[7];
          $encoded_cin[1] = $cin[0];
          $encoded_cin[2] = $cin[5];
          $encoded_cin[3] = $cin[10];
          $encoded_cin[4] = $cin[8];
          $encoded_cin[5] = $cin[4];
          $encoded_cin[6] = $cin[3];
          $encoded_cin[7] = $cin[1];
          $encoded_cin[8] = $cin[9];
          $encoded_cin[9] = $cin[2];
          $encoded_cin[10] = $cin[6];
          $encoded_cin[11] = $cin[11];
          break;
        case '9':
          $encoded_cin[0] = $cin[7];
          $encoded_cin[1] = $cin[0];
          $encoded_cin[2] = $cin[5];
          $encoded_cin[3] = $cin[10];
          $encoded_cin[4] = $cin[8];
          $encoded_cin[5] = $cin[4];
          $encoded_cin[6] = $cin[3];
          $encoded_cin[7] = $cin[1];
          $encoded_cin[8] = $cin[9];
          $encoded_cin[9] = $cin[2];
          $encoded_cin[10] = $cin[6];
          $encoded_cin[11] = $cin[11];
          break;

        default;

          break;
      }
      return $encoded_cin;
    }

    public function cin_decoder($cin){
      $len = strlen($cin);
      $dencoder = $cin[$len-1];
      $dencoded_cin = $cin;
      switch ($encoder) {
        case '0':
          $dencoded_cin[7] = $cin[0];
          $dencoded_cin[0] = $cin[1];
          $dencoded_cin[5] = $cin[2];
          $dencoded_cin[10] = $cin[3];
          $dencoded_cin[8] = $cin[4];
          $dencoded_cin[4] = $cin[5];
          $dencoded_cin[3] = $cin[6];
          $dencoded_cin[1] = $cin[7];
          $dencoded_cin[9] = $cin[8];
          $dencoded_cin[2] = $cin[9];
          $dencoded_cin[8] = $cin[10];
          $dencoded_cin[11] = $cin[11];
          break;
        case '1':
          $dencoded_cin[1] = $cin[0];
          $dencoded_cin[8] = $cin[1];
          $dencoded_cin[2] = $cin[2];
          $dencoded_cin[8] = $cin[3];
          $dencoded_cin[0] = $cin[4];
          $dencoded_cin[3] = $cin[5];
          $dencoded_cin[5] = $cin[6];
          $dencoded_cin[10] = $cin[7];
          $dencoded_cin[7] = $cin[8];
          $dencoded_cin[4] = $cin[9];
          $dencoded_cin[9] = $cin[10];
          $dencoded_cin[11] = $cin[11];
          break;
        case '2':
          $dencoded_cin[10] = $cin[0];
          $dencoded_cin[2] = $cin[1];
          $dencoded_cin[4] = $cin[2];
          $dencoded_cin[8] = $cin[3];
          $dencoded_cin[3] = $cin[4];
          $dencoded_cin[5] = $cin[5];
          $dencoded_cin[7] = $cin[6];
          $dencoded_cin[9] = $cin[7];
          $dencoded_cin[0] = $cin[8];
          $dencoded_cin[6] = $cin[9];
          $dencoded_cin[1] = $cin[10];
          $dencoded_cin[11] = $cin[11];
          break;
        case '3':
          $dencoded_cin[5] = $cin[0];
          $dencoded_cin[9] = $cin[1];
          $dencoded_cin[1] = $cin[2];
          $dencoded_cin[4] = $cin[3];
          $dencoded_cin[10] = $cin[4];
          $dencoded_cin[3] = $cin[5];
          $dencoded_cin[0] = $cin[6];
          $dencoded_cin[2] = $cin[7];
          $dencoded_cin[6] = $cin[8];
          $dencoded_cin[7] = $cin[9];
          $dencoded_cin[8] = $cin[10];
          $dencoded_cin[11] = $cin[11];
          break;
        case '4':
          $dencoded_cin[8] = $cin[0];
          $dencoded_cin[3] = $cin[1];
          $dencoded_cin[7] = $cin[2];
          $dencoded_cin[0] = $cin[7];
          $dencoded_cin[9] = $cin[4];
          $dencoded_cin[2] = $cin[5];
          $dencoded_cin[4] = $cin[6];
          $dencoded_cin[6] = $cin[7];
          $dencoded_cin[1] = $cin[8];
          $dencoded_cin[5] = $cin[9];
          $dencoded_cin[10] = $cin[10];
          $dencoded_cin[11] = $cin[11];
          break;
        case '5':
          $dencoded_cin[7] = $cin[0];
          $dencoded_cin[0] = $cin[1];
          $dencoded_cin[5] = $cin[2];
          $dencoded_cin[10] = $cin[3];
          $dencoded_cin[8] = $cin[4];
          $dencoded_cin[4] = $cin[5];
          $dencoded_cin[3] = $cin[6];
          $dencoded_cin[1] = $cin[7];
          $dencoded_cin[9] = $cin[8];
          $dencoded_cin[2] = $cin[9];
          $dencoded_cin[6] = $cin[10];
          $dencoded_cin[11] = $cin[11];
          break;
        case '6':
          $dencoded_cin[7] = $cin[0];
          $dencoded_cin[0] = $cin[1];
          $dencoded_cin[5] = $cin[2];
          $dencoded_cin[10] = $cin[3];
          $dencoded_cin[8] = $cin[4];
          $dencoded_cin[4] = $cin[5];
          $dencoded_cin[3] = $cin[6];
          $dencoded_cin[1] = $cin[7];
          $dencoded_cin[9] = $cin[8];
          $dencoded_cin[2] = $cin[9];
          $dencoded_cin[8] = $cin[10];
          $dencoded_cin[11] = $cin[11];
          break;
        case '7':
          $dencoded_cin[7] = $cin[0];
          $dencoded_cin[1] = $cin[1];
          $dencoded_cin[2] = $cin[5];
          $dencoded_cin[3] = $cin[10];
          $dencoded_cin[4] = $cin[8];
          $dencoded_cin[5] = $cin[4];
          $dencoded_cin[6] = $cin[3];
          $dencoded_cin[7] = $cin[1];
          $dencoded_cin[8] = $cin[9];
          $dencoded_cin[9] = $cin[2];
          $dencoded_cin[10] = $cin[6];
          $dencoded_cin[11] = $cin[11];
          break;
        case '8':
          $dencoded_cin[0] = $cin[7];
          $dencoded_cin[1] = $cin[0];
          $dencoded_cin[2] = $cin[5];
          $dencoded_cin[3] = $cin[10];
          $dencoded_cin[4] = $cin[8];
          $dencoded_cin[5] = $cin[4];
          $dencoded_cin[6] = $cin[3];
          $dencoded_cin[7] = $cin[1];
          $dencoded_cin[8] = $cin[9];
          $dencoded_cin[9] = $cin[2];
          $dencoded_cin[10] = $cin[6];
          $dencoded_cin[11] = $cin[11];
          break;
        case '9':
          $dencoded_cin[0] = $cin[7];
          $dencoded_cin[1] = $cin[0];
          $dencoded_cin[2] = $cin[5];
          $dencoded_cin[3] = $cin[10];
          $dencoded_cin[4] = $cin[8];
          $dencoded_cin[5] = $cin[4];
          $dencoded_cin[6] = $cin[3];
          $dencoded_cin[7] = $cin[1];
          $dencoded_cin[8] = $cin[9];
          $dencoded_cin[9] = $cin[2];
          $dencoded_cin[10] = $cin[6];
          $dencoded_cin[11] = $cin[11];
          break;

        default;

          break;
      }
      return $encoded_cin;
    }


}
