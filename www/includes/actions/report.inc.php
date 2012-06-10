<?php

if(! defined('arms2')) die('No direct script access allowed');
if(strpos($_SESSION['arms2']['privs'], 's') === false)
	die('You do not have the Squad Report privilege');
echo '<h3>ARMS report</h3>';

if (isset($_POST['submit'])) {
    $sx = strip_tags($_POST['x1']);
    $sy = strip_tags($_POST['y1']);
    $ex = strip_tags($_POST['x2']);
    $ey = strip_tags($_POST['y2']);

    $buildings = array(0 => array(0 => "Bird Boulevard", 1 => "Palprey Road Police Department", 2 => "St. Danilo's Church", 3 => "a cemetary", 4 => "a carpark", 5 => "Hewett Library", 6 => "Geldart Square", 7 => "Hardwick Row Railway Station", 8 => "the Bullimore Arms", 9 => "Bourdillion Library"), 
		       1 => array(0 => "the Norgan Building", 1 => "Patridge Grove", 2 => "Club Ogburn", 3 => "Gatley Drive", 4 => "Seamour Crescent", 5 => "Merivale Crescent", 6 => "St Ansgar's Hospital", 7 => "a warehouse", 8 => "a warehouse", 9 => "Cuthbert Auto Repair"),
		       2=> array(0 => "Sinkins Auto Repair", 1 => "Scorse Plaza", 2 => "Voules Walk", 3 => "Pavey Place School", 4 => "Cranton Library", 5 => "Glastonbury Alley", 6 => "Ditcher Lane", 7 => "Heward Towers", 8 => "Bedford Street", 9 => "the Boucher Arms"),
		       3 => array(0 => "the Emberson Building", 1 => "the Dibbin Building", 2 => "wasteland", 3 => "Dovey Boulevard Fire Station", 4 => "a warehouse", 5 => "Owens Walk", 6 => "Tomkins Bank", 7 => "McDougall Cinema", 8 => "the Yelling Building", 9 => "the Hodgkins Arms"),
		       4 => array(0 => "Club Magee", 1 => "Wilmott Row", 2 => "Turpin Crescent", 3 => "the Tobit Arms", 4 => "the Doyne Hotel", 5 => "a junkyard", 6 => "Bissley Bank", 7 => "Turnock Place", 8 => "Hopes Library", 9 => "Alderson Street"),
		       5 => array(0 => "Wale Walk Police Dept", 1 => "Buckinham Lane", 2 => "a warehouse", 3 => "St Bartholomew's Hospital", 4 => "the Buttle Building", 5 => "Montacute Place", 6 => "the Oakes Museum", 7 => "Andow Square", 8 => "wasteland", 9 => "Imber Road Railway Station"),
		       6 => array(0 => "a warehouse", 1 => "the Gristwood Hotel", 2 => "Swearse Lane Police Dept", 3 => "a factory", 4 => "Owsley Alley", 5 => "Petvin Place", 6 => "a warehouse", 7 => "a carpark", 8 => "Woolsett Avenue", 9 => "Rodges Grove"),
		       7 => array(0 => "Hogue Street", 1 => "a junkyard", 2 => "Lomas Boulevard Fire Station", 3 => "Woodborne Crescent", 4 => "wasteland", 5 => "Tossell Walk", 6 => "a junkyard", 7 => "Tolly Library", 8 => "Ledger Avenue", 9 => "the Blackman Building"),
		       8 => array(0 => "St Matthias's Church", 1 => "Jeffries Auto Repair", 2 => "Britton Park", 3 => "wasteland", 4 => "the Beal Building", 5 => "the Chitty Hotel", 6 => "Leeworthy Park", 7 => "the Pask Building", 8 => "Calderwood Plaza", 9 => "Meleady Plaza"),
		       9 => array(0 => "Stobbart Walk Police Dept", 1 => "Freeth Auto Repair", 2 => "the Crespin Building", 3 => "Tyack Row", 4 => "Chadwick Towers", 5 => "Atkinson Square", 6 => "Hazeldine Square", 7 => "the Troakes Monument", 8 => "Snook Alley Railway Station", 9 => "Whitesides Row"), 
		       20 => array(0 => "the Orr Building", 1 => "a junkyard", 2 => "the Coss Building", 3 => "Alkin Cinema", 4 => "the Hockey Museum", 5 => "Powell Place", 6 => "Tufton Lane", 7 => "Wookey Towers", 8 => "Peitevin Alley Railway Station", 9 => "Red Alley", 10 => "a factory", 11 => "Petty Place School", 12 => "the Purchas Building", 13 => "Pasker Place", 14 => "Gamlen Plaza", 15 => "a factory", 16 => "Lockwood Crescent School", 17 => "Rawlins Row", 18 => "Goudie Drive", 19 => "Kington Row"),  
		       21 => array(0 => "Habgood Avenue", 1 => "Mattick Walk Fire Station", 2 => "Channell Lane Fire Station", 3 => "Tipton Road", 4 => "the Stockham Monument", 5 => "Burchall Way", 6 => "Rush Grove", 7 => "Butson Boulevard School", 8 => "a carpark", 9 => "Kelher Park", 10 => "Newton Walk",       11 => "Lasbury Cinema",       12 => "Roadnight Auto Repair",       13 => "Lawrance Crescent School",       14 => "wasteland",       15 => "Regan Plaza",       16 => "Nettley Drive",       17 => "Strachan Lane",       18 => "Fowler Crescent Railway Station",       19 => "Golden Row"),
		       22 => array(0 => "the Baboneau Arms", 1 => "Finlay Walk", 2 => "Prouse Towers", 3 => "Bondfield Road", 4 => "the Elderfield Building", 5 => "Gidley Street", 6 => "Drave Walk", 7 => "Herrin Way", 8 => "Jayne Walk", 9 => "St Edith's Church", 10 => "the Rodges Building", 11 => "Carritt Drive", 12 => "St. Luke's Hospital", 13 => "Barter Row Railway Station", 14 => "Pottenger Boulevard", 15 => "Bulmer Avenue", 16 => "Dight Walk", 17 => "a carpark", 18 => "Zuryk Lane", 19 => "Somerville Boulevard", ),
		       23 => array(0 => "Fitkin Auto Repair", 1 => "Rainey Grove", 2 => "Pallaye Plaza", 3 => "Swinnerton Square Police Dept", 4 => "the Steel Motel", 5 => "the Harbord Arms", 6 => "wasteland", 7 => "the Avery Arms", 8 => "the Huxtable Building", 9 => "the Giblin Arms", 10 => "a cemetery", 11 => "St. Mark's Church", 12 => "a cemetery", 13 => "the Loder Museum", 14 => "Apsey Library", 15 => "Kenefie Lane Police Department", 16 => "Cuthbert General Hospital", 17 => "a junkyard", 18 => "Shutler Lane", 19 => "the Bowell Museum"),
		       24 => array(0 => "Haberfeild Drive", 1 => "a factory", 2 => "Fernie Walk", 3 => "Vasey Lane", 4 => "Houghton Towers", 5 => "Metcalfe Library", 6 => "Phipps Library", 7 => "the Lenthall Motel", 8 => "Chick Street", 9 => "the Brickenden Monument", 10 => "Coollen Lane School", 11 => "Baring Auto Repair", 12 => "Ramsey Road", 13 => "Downton Square", 14 => "Club Hodson", 15 => "Carpenter Grove", 16 => "Tennant Auto Repair", 17 => "Stringer Street", 18 => "wasteland", 19 => "Custard Lane School"),
		       25 => array(0 => "Rawlings Road", 1 => "Hutson Crescent", 2 => "a factory", 3 => "St Humphrey's Church", 4 => "a carpark", 5 => "Sibree Plaza", 6 => "Smethes Crescent", 7 => "Julian Lane", 8 => "Dommett Road", 9 => "Beagly Lane", 10 => "the Fiddes Monument", 11 => "Spalding Walk", 12 => "Willshire Towers", 13 => "the Harraway Building", 14 => "a junkyard", 15 => "the Hawtrey Museum", 16 => "Ivens Park", 17 => "the Vetch Building", 18 => "Thristle Bank", 19 => "Fanning Street"),
		       26 => array(0 => "Golde Avenue", 1 => "Beale Walk", 2 => "Donagan Alley", 3 => "the Bascombe Building", 4 => "Hart Grove", 5 => "Tooze Bank", 6 => "Nich Library", 7 => "Frengrove Walk", 8 => "Messiter Place School", 9 => "the Hookins Building", 10 => "the Minshull Building", 11 => "a warehouse", 12 => "Roadnight Cinema", 13 => "Reid Library", 14 => "the Binney Monument", 15 => "Clifden Way", 16 => "Howard Library", 17 => "Cother Square", 18 => "a carpark", 19 => "Silwood Crescent"),
		       27 => array(0 => "Lyne Lane", 1 => "Book Boulevard", 2 => "Kray Walk", 3 => "Honeyben Drive", 4 => "Crossley Road", 5 => "Club Veal", 6 => "Parrott Cinema", 7 => "Jaques Drive", 8 => "the Wootton Building", 9 => "Demack Row Fire Station", 10 => "the Stothert Monument", 11 => "Sletery Cinema", 12 => "Rodwell Row Police Department", 13 => "Younghusband Square", 14 => "the Donovan Building", 15 => "Knyfton Row Fire Station", 16 => "Fords Lane", 17 => "the Glastonbury Motel", 18 => "Eden Library", 19 => "Caiger Mall"),
		       28 => array(0 => "Burnley Square", 1 => "Club Verncomb", 2 => "the Geldeard Monument", 3 => "the Bondfield Building", 4 => "Edmund General Hospital", 5 => "Haw Alley", 6 => "Culmstock Park", 7 => "Bunter Street Police Dept", 8 => "McCormack Square Fire Station", 9 => "Savidge Grove School", 10 => "the Bristol Building", 11 => "Mant Library", 12 => "Craven Way", 13 => "the Ratcliffe Motel", 14 => "Rome Way", 15 => "Doyne Street", 16 => "the Mapledoram Monument", 17 => "Ogilvie Place", 18 => "the Tynte Building", 19 => "Caiger Mall"),
		       29 => array(0 => "wasteland", 1 => "the Broadrick Arms", 2 => "Bergman Square", 3 => "Crosse Boulevard Fire Station", 4 => "Herick Lane", 5 => "a warehouse", 6 => "Hersant Auto Repair", 7 => "Softley Park", 8 => "Goodford Avenue", 9 => "Lecrus Alley", 10 => "Pirrie Way", 11 => "Hevey Row", 12 => "wasteland", 13 => "the Waugh Arms", 14 => "Rowse Cinema", 15 => "the Tiplot Museum", 16 => "a warehouse", 17 => "Bernard Towers", 18 => "the Hussey Monument", 19 => "the Hearne Building"),
		       30 => array(0 => "Tharratt Road", 1 => "Squires Crescent", 2 => "Catherine General Hospital", 3 => "Side Alley", 4 => "Studley Towers", 5 => "Hakens Way", 6 => "Dalwood Lane", 7 => "the Pettman Museum", 8 => "Salt Towers", 9 => "wasteland", 10 => "Stranger Grove Fire Station", 11 => "the Sell Monument", 12 => "the Acott Arms", 13 => "Feaver Walk", 14 => "Vyse Row", 15 => "Paisley Road", 16 => "Maggs Bank", 17 => "Flemming Lane", 18 => "a junkyard", 19 => "the Sweeney Building"),
		       31 => array(0 => "the Moon Building", 1 => "Club Swain", 2 => "St Justin's Hospital", 3 => "Woodgate Avenue", 4 => "the Roger Hotel", 5 => "Clarry Towers", 6 => "Main Walk", 7 => "Club Illing", 8 => "Henderson Boulevard", 9 => "Prinn Drive", 10 => "Worner Crescent", 11 => "the Ackermen Hotel", 12 => "wasteland", 13 => "Ross Avenue School", 14 => "Chorley Drive", 15 => "the Oxley Building", 16 => "Whippey Place Fire Station", 17 => "a junkyard", 18 => "wasteland", 19 => "Bentley Cinema"),
		       32 => array(0 => "St Luke's Church", 1 => "Raynols Boulevard", 2 => "the Puckle Monument", 3 => "Bigg Boulevard", 4 => "Stephen General Hospital", 5 => "Polley Grove", 6 => "Brimson Alley Railway Station", 7 => "Powlet Grove", 8 => "Yea Drive Police Dept", 9 => "Lolley Library", 10 => "Tuchings Park", 11 => "a carpark", 12 => "Mather Bank", 13 => "Ker Way", 14 => "Curle Street Police Dept", 15 => "Pullin Square", 16 => "Damon Way", 17 => "St Lazar's Hospital", 18 => "a junkyard", 19 => "the Ayliffee Building"),
		       33 => array(0 => "the Jacomb Arms", 1 => "Cotty Street Police Dept", 2 => "a factory", 3 => "the Neate Monument", 4 => "Parsley Road", 5 => "Craigie Alley", 6 => "St Marcus's Church", 7 => "Hilborn Walk", 8 => "Eckersley Cinema", 9 => "Bendle Drive", 10 => "Sroud Crescent", 11 => "Mears Auto Repair", 12 => "wasteland", 13 => "Goldsworthy Avenue", 14 => "Uppill Library", 15 => "Creese Way", 16 => "Ripley Park", 17 => "Hardstaff Bank", 18 => "the Caddick Monument", 19 => "Coss Cinema"),
		       34 => array(0 => "Gould Walk", 1 => "the Heal Museum", 2 => "Combe Lane", 3 => "Inclesdon Drive", 4 => "a junkyard", 5 => "North Lane", 6 => "Hartry Crescent", 7 => "the Fortune Building", 8 => "Blight Park", 9 => "the Golling Building", 10 => "Help Bank", 11 => "Capper Alley Railway Station", 12 => "a junkyard", 13 => "Freeguard Walk", 14 => "Piegsa Place Police Dept", 15 => "Boon Crescent", 16 => "Nicols Plaza", 17 => "wasteland", 18 => "Griffen Square", 19 => "the McDougall Building"),
		       35 => array(0 => "Coffins Drive", 1 => "Heming Way", 2 => "Dewfall Plaza Railway Station", 3 => "Swonnell Walk", 4 => "Anderson Row School", 5 => "Shilling Walk", 6 => "Billet Auto Repair", 7 => "Broadbelt Grove Police Dept", 8 => "wasteland", 9 => "Ainslie Road Fire Station", 10 => "the Travers Building", 11 => "the Tatchell Building", 12 => "St Tsarevna's Church", 13 => "Enright Boulevard", 14 => "the Gotch Museum", 15 => "the Gass Monument", 16 => "Bridgewater Drive", 17 => "Beel Boulevard", 18 => "Lockie Row", 19 => "Edgerton Drive"),
		       36 => array(0 => "Newten Cinema", 1 => "Wakley Boulevard", 2 => "Ostrehan Towers", 3 => "Godsland Street School", 4 => "the McGarth Building", 5 => "the Dury Building", 6 => "Zephyrinus General Hospital", 7 => "Club Meade", 8 => "Coorte Square", 9 => "Standen Row", 10 => "the Fennessy Building", 11 => "Tibbs Row", 12 => "the Nolan Hotel", 13 => "Dorey Way", 14 => "wasteland", 15 => "a junkyard", 16 => "Mallard Library", 17 => "Duport Alley", 18 => "Bowle Place", 19 => "Kempthorne Cinema"),
		       37 => array(0 => "the Stone Motel", 1 => "Neyens Avenue", 2 => "Newbury Library", 3 => "Arbuthnot Park", 4 => "a factory", 5 => "Molesworth Road", 6 => "St Lorenzo's Church", 7 => "Comer Avenue", 8 => "St Mary's Church", 9 => "Glass Park", 10 => "McDermott Park", 11 => "Graham Towers", 12 => "St Osyth's Church", 13 => "Ayre Place Railway Station", 14 => "Pownall Alley", 15 => "wasteland", 16 => "the Bawn Museum", 17 => "Farlow Drive School", 18 => "Ash Walk", 19 => "the Mitchener Monument"),
		       38 => array(0 => "the Hatson Building", 1 => "the Mashman Building", 2 => "a carpark", 3 => "Horn Walk", 4 => "Brendan General Hospital", 5 => "Penny Crescent", 6 => "Fullaway Crescent", 7 => "the Tibbs Monument", 8 => "a cemetery", 9 => "a factory", 10 => "Emes Walk", 11 => "Sevior Plaza", 12 => "a cemetery", 13 => "Dunstone Walk Fire Station", 14 => "Powers Avenue School", 15 => "Quaney Alley", 16 => "Doggrell Avenue", 17 => "a junkyard", 18 => "a carpark", 19 => "Swetman Park"),
		       39 => array(0 => "Bowley Lane", 1 => "Summers Row", 2 => "Basher Street", 3 => "wasteland", 4 => "Ritchie Boulevard", 5 => "Lucius General Hospital", 6 => "wasteland", 7 => "Leader Drive", 8 => "Bush Crescent Fire Station", 9 => "a carpark", 10 => "Urben Alley Fire Station", 11 => "the Cavill Building", 12 => "Cottam Way Police Dept", 13 => "the Flowar Building", 14 => "St Werburgh's Hospital", 15 => "Verrall Park", 16 => "Blaimen Street", 17 => "St Marcus's Church", 18 => "a cemetery", 19 => "Gibbins Towers"),
		       40 => array(0 => "a warehouse", 1 => "Leggetter Library", 2 => "a junkyard", 3 => "Parkhouse Way Fire Station", 4 => "the Hyson Museum", 5 => "Varder Walk", 6 => "the Pile Building", 7 => "the Hellyer Building", 8 => "the Cribb Building", 9 => "Smallwood Plaza", 10 => "Mitchel Walk", 11 => "Dauncey Square", 12 => "a junkyard", 13 => "Foyle Lane", 14 => "a carpark", 15 => "wasteland", 16 => "Bletso Cinema", 17 => "the Hambidge Building", 18 => "a warehouse", 19 => "Montgomery Avenue Fire Station"),
		       41 => array(0 => "Goodson Square", 1 => "the Armastrong Monument", 2 => "the Barber Building", 3 => "Borthwick Alley", 4 => "Hopes Row", 5 => "St Columbanus's Church", 6 => "wasteland", 7 => "Mains Alley", 8 => "the Troakes Museum", 9 => "the Cother Museum", 10 => "the Bayford Building", 11 => "Tasker Park", 12 => "Aston Lane", 13 => "St Paulinus's Church", 14 => "the Hippesley Building", 15 => "Hamm Walk Fire Station", 16 => "Pattinson Row", 17 => "Trimble Lane", 18 => "the Barstow Building", 19 => "Loveridge Drive"),
		       42 => array(0 => "Bird Boulevard", 1 => "a carpark", 2 => "wasteland", 3 => "Hazzard Walk", 4 => "Deed Lane", 5 => "wasteland", 6 => "the Dennis Motel", 7 => "Jervis Auto Repair", 8 => "Club Burns", 9 => "Caff Road School", 10 => "the Pankhurst Building", 11 => "Wadden Boulevard", 12 => "a carpark", 13 => "Cottey Way", 14 => "a cemetery", 15 => "the Wakeford Motel", 16 => "a carpark", 17 => "Lettey Row Fire Station", 18 => "a warehouse", 19 => "the Pridham Building"),
		       43 => array(0 => "Hardie Square", 1 => "Garson Row", 2 => "Polley Way", 3 => "the Pers Monument", 4 => "Garland Library", 5 => "Aloysius General Hospital", 6 => "a carpark", 7 => "McNamara Drive", 8 => "Tailer Row", 9 => "Hawtrey Alley", 10 => "Seear Auto Repair", 11 => "Emms Walk", 12 => "Tidball Library", 13 => "Ryles Avenue", 14 => "Chaning Alley", 15 => "Shenton Crescent", 16 => "Hollyman Lane", 17 => "Bustin Auto Repair", 18 => "Cotty Bank", 19 => "Tarzwell Road"),
		       44 => array(0 => "the Woodborne Building", 1 => "the Dawney Building", 2 => "wasteland", 3 => "Dobin Auto Repair", 4 => "Sankey Boulevard", 5 => "Holide Way", 6 => "Eelms Avenue", 7 => "the Goode Building", 8 => "Howard Auto Repair", 9 => "Ruggevale Walk Police Dept", 10 => "Bere Towers", 11 => "Riste Alley", 12 => "Frekee Walk", 13 => "Burrell Way Police Dept", 14 => "Julian Lane", 15 => "Rodeney Plaza", 16 => "the Bampfyld Arms", 17 => "the Eglen Building", 18 => "a factory", 19 => "Bygrave Cinema"),
		       45 => array(0 => "Ellicott Place Railway Station", 1 => "Hodgkinson Drive", 2 => "Dalgliesh Cinema", 3 => "Beale Library", 4 => "Matcham Square Railway Station", 5 => "Vesey Cinema", 6 => "McCullough Avenue", 7 => "a carpark", 8 => "the Crosswell Building", 9 => "Verrell Crescent", 10 => "Perham Park", 11 => "Wallbutton Way", 12 => "the Austwick Museum", 13 => "Warner Boulevard", 14 => "Falvey Walk Fire Station", 15 => "Ewins Row Fire Station", 16 => "Hind Boulevard", 17 => "Sorton Way Fire Station", 18 => "Rumbell Grove Railway Station", 19 => "Sherman Alley Fire Station"),
		       46 => array(0 => "Waller Auto Repair", 1 => "Thirlby Walk", 2 => "Randell Boulevard", 3 => "a carpark", 4 => "Wild Place", 5 => "Griff Drive School", 6 => "the Pitts Museum", 7 => "a carpark", 8 => "Mitchard Park", 9 => "a junkyard", 10 => "Harrison Park", 11 => "Jouxson Cinema", 12 => "Stallworthy Square", 13 => "a factory", 14 => "the Farrant Monument", 15 => "Blockwood Auto Repair", 16 => "Holland Alley", 17 => "wasteland", 18 => "the Lee Building", 19 => "the Snee Building"),
		       47 => array(0 => "the Sendall Monument", 1 => "Howord Way", 2 => "Percifull Plaza", 3 => "Bisshop Square", 4 => "the Dunning Motel", 5 => "a factory", 6 => "Cudworth Alley", 7 => "Harewood Library", 8 => "Harington Boulevard", 9 => "Much Boulevard", 10 => "the Vaughan Arms", 11 => "the Hateley Arms", 12 => "St Jude's Cathedral", 13 => "St Jude's Cathedral", 14 => "Cape Towers", 15 => "the Blackburn Arms", 16 => "Hanlon Park", 17 => "Denbury Road", 18 => "Felix General Hospital", 19 => "the Hamilton Arms"),
		       48 => array(0 => "a junkyard", 1 => "St Daniel's Hospital", 2 => "wasteland", 3 => "the Willison Building", 4 => "a junkyard", 5 => "a factory", 6 => "Shattock Plaza School", 7 => "Scudamore Plaza Fire Station", 8 => "the Duncan Monument", 9 => "a carpark", 10 => "Stockley Walk Police Dept", 11 => "Maishman Bank", 12 => "St Jude's Cathedral", 13 => "St Jude's Cathedral", 14 => "Copleston Library", 15 => "the Dimon Museum", 16 => "the Collings Building", 17 => "St Elisabeth's Hospital", 18 => "Badman Cinema", 19 => "Brundrit Towers"),
		       49 => array(0 => "Kittle Alley School", 1 => "Boyer Boulevard Railway Station", 2 => "Prideaux Street Fire Station", 3 => "Wilshe Drive", 4 => "Comitty Alley", 5 => "the Whyte Monument", 6 => "the Challes Building", 7 => "Collis Library", 8 => "Lye Alley", 9 => "the Russel Museum", 10 => "Martindale Road", 11 => "the Curme Building", 12 => "Coffins Lane School", 13 => "Donnan Alley", 14 => "Thorburn Way", 15 => "a factory", 16 => "Neary Bank", 17 => "the Reginaldus Museum", 18 => "Crowly Library", 19 => "St Columba's Church"));



# connect to db
$mysqli = new mysqli('localhost', 'dennisse_arms', $config['pwd'], 'dennisse_arms');

	$sql = <<<SQL
		select
			barricades.x as x, barricades.y as y,
			barricades.cades as cades, barricades.stamp as stamp,
			status_indoors.genny as genny, status_indoors.zeds as zeds_in,
			status_outdoors.zeds as zeds_out
			from barricades
		left join status_indoors on barricades.x = status_indoors.x
			and barricades.y = status_indoors.y
		left join status_outdoors on barricades.x = status_outdoors.x
			and barricades.y = status_outdoors.y
		where barricades.x >= ? and barricades.x <= ?
			and barricades.y >= ? and barricades.y <= ?
		order by barricades.y, barricades.x
SQL;
	$stmt = $mysqli->stmt_init();
	if(! $stmt->prepare($sql)) die(json_encode(
		array('error' => 'pull block status failure')));
	$stmt->bind_param('iiii', $sx, $ex, $sy, $ey);
	$stmt->execute();
	$stmt->store_result();
	$status = array();
	$stmt->bind_result($x, $y, $cades, $stamp, $genny, $zeds_in, $zeds_out);

	while($stmt->fetch())
	{
	  $age = round(time() - $stamp) ;
	  $hours = intval(floor($age / 3600));
	  if ($hours < 240) {
	    if (($cades && $cades != "z") || $genny || $zeds_in || $zeds_out) {
	    $name = $buildings[$y][$x];
	    print "<b>[$x, $y] $name</b> (";
	    $printflag = 0;
	    if ($cades) {
	      if ($cades != "z") {
		print "cades: $cades, ";
		$printflag = 1;
	      }
	    }
	    if ($genny) {
	      print "$genny, ";
	      $printflag = 1;
	    }
	    if ($zeds_in) {
	      print "$zeds_in inside, ";
	      $printflag = 1;
	    } 
	    if ($zeds_out) {
	      print "$zeds_out outside, ";
	      $printflag = 1;
	    }
	    if ($printflag) {
	      print "$hours hours ago";
	    }
	    print ")<br>";
	    }
	  }
	  $stamp = 5000;
	}

  }
?>
<p>Please list desired Report Coordinates:</p>

<form method="post" action="<?=$SERVER['PHP_SELF']?>">
<table><tr><td>Top Corner</td><td>X: <textarea name="x1" cols=3 rows=1></textarea></td><td>Y: <textarea name="y1" cols=3 rows=1></textarea></td></tr>
<tr><td>Bottom Corner</td><td>X: <textarea name="x2" cols=3 rows=1></textarea></td><td>Y: <textarea name="y2" cols=3 rows=1></textarea></td></tr></table>
<input type="submit" name="submit" value="Submit"/>
</form>


