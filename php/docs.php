<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="de" class=" js flexbox flexboxlegacy datauri"><head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0"> 

  <title>Hochschule Hof - Manual </title>

<link rel="stylesheet" type="text/css" href="./css/fontello.css" media="screen">
<link rel="stylesheet" type="text/css" href="./css/theme-base.css" media="screen">
<link rel="stylesheet" type="text/css" href="./css/theme-medium.css" media="screen">
</head>
<body class="docs ">

<nav id="head-nav" class="navbar navbar-fixed-top">
  <div class="navbar-inner clearfix">
    <div id="mainmenu-toggle-overlay"></div>
    <input type="checkbox" id="mainmenu-toggle">
    <ul class="nav">
      <li class="active"><a href="#">Documentation</a></li>
    </ul>
  </div>
  <div id="flash-message"></div>

</nav>
<div class="headsup">Version 3.4</div>


<div id="layout" class="clearfix">
  <section id="layout-content">
<div class="refentry">
 <div class="refnamediv">
  <h1 class="refname">Studiengänge</h1>
  <p class="refpurpose"><span class="refname">Version 1</span> — <span class="dc-title">Basisfunktionen (Studiengänge, Stundenplan, Änderungen, Stundenplan mit integrierten Änderungen) hinzugefügt.</span></p>
  <p class="refpurpose"><span class="refname">Version 2</span> — <span class="dc-title">Neuer Übergabeparameter <strong>id</strong> wurde hinzugefügt.</span></p>
  <p class="refpurpose"><span class="refname">Version 3</span> — <span class="dc-title">Neuer Übergabeparameter <strong>tt</strong> wurde hinzugefügt.</span></p>
  <p class="refpurpose"><span class="refname">Version 3.1</span> — <span class="dc-title">Neue Funktion MySchedule verfügbar.</span></p>
  <p class="refpurpose"><span class="refname">Version 3.2</span> — <span class="dc-title">Bei getMySchedule werden nun auch Comment mit übergeben<br />
  Bei getChanges kann man nun auch eine Anfrage stellen mit nur ID's um für die ID's die Änderungen zu bekommen<br />
  Fix das man auch Änderungen für das kommende Jahr bekommt</span></p>
  <p class="refpurpose"><span class="refname">Version 3.3</span> — <span class="dc-title">Bei folgenden Methoden wird nun anstatt der ID's der Vorlesungen, nun der splusname der Vorlesungen mit übergeben:<br />
  - getSchedule() (optionaler 4.ter Parameter)<br />
  - getMySchedule()<br />
  - getMergedSchedule() (optionaler 4.ter Parameter)<br />
  - getChanges() (optionaler 4.ter Parameter)<br />
  "type" beinhaltet nun Kürzel wie "FWPM", "AWPM" usw. Die vorherige Information von "type" (meistens einfach nur "Vorlesung"), heißt nun "style"<br />
  Außerdem besteht die Möglichkeit sich für Push-Benachrichtigungen per FCM (Firebase Cloud Messaging) zu registrieren.</span></p>
  <p class="refpurpose"><span class="refname">Version 3.4</span> — <span class="dc-title">Die Information sp wird nun mit übergeben.<br />
  Sie beinhaltet "-" wenn keiner vorhanden ist und z.B. "SP (MB)" wenn ein SP vorhanden ist.</span></p>
 </div>

 <div class="refsect1 description">
  <h3 class="title">Beschreibung</h3>
  <div class="methodsynopsis dc-description">
   <span class="type">JsonObject</span> <span class="methodname"><strong>client.php?f=</strong></span>(<span class="type">String</span> <span class="methodname"><strong>function</strong></span>)&stg=(<span class="type">String</span> <span class="methodname"><strong>course</strong></span>)&sem=(<span class="type">String</span> <span class="methodname"><strong>semester</strong></span>)&tt=(<span class="type">String</span> <span class="methodname"><strong>tt</strong></span>)&id[]=(<span class="type">array</span> <span class="methodname"><strong>id</strong></span> (multiple))
  </div>

  <p class="para rdfs-comment">Liefert alle Studiengänge des aktuellen Jahres und Semesterhalbjahres (WS/SS) alphabetisch sortiert.</p><div class="example-contents">
 </div>

 <div class="parameters">
  <h3 class="title">Parameter-Liste</h3>
  <dl>
      <dt><code class="parameter">function</code></dt>
      <dd>
          <p class="para">
            Die Funktionen <strong><code>Schedule</code></strong>, <strong><code>Changes</code></strong> oder <strong><code>MSchedule</code></strong> erfordern die Parameter <span class="methodname">(<strong>stg, sem, tt, id (optional)</strong>)</span> und können über den <strong>optional</strong> ergänzt werden.<br>
                Die Funktion <strong><code>MySchedule</code></strong> erfordert <strong>ausschließlich</strong> den Parameter (<span class="methodname"><strong>id</strong></span>).<br/>
            Die Funktion <strong><code>Courses</code></strong> erfordert <strong>ausschließlich</strong> den Parameter (<span class="methodname"><strong>tt</strong></span>).
          </p>
      </dd>
      <dt><code class="parameter">course</code></dt>
      <dd>
          <p class="para">
            Das Studiengangkürzel des Studienganges
          </p>
      </dd>
      <dt><code class="parameter">semester</code></dt>
      <dd>
          <p class="para">
            Das Semesterjahr des Studienganges
          </p>
      </dd>
      <dt><code class="parameter">tt</code></dt>
      <dd>
          <p class="para">
            Das Semesterhalbjahr (SS/WS)
          </p>
      </dd>      
      <dt><code class="parameter">id</code></dt>
      <dd>
          <p class="para">
              Es werden nur noch die Datensätze angezeigt, dessen <strong><code>id</code></strong> im Array enthalten ist. Die Werte von <strong><code>id</code></strong> sind gleich der Datenbanktabelle <strong><code>Stundenplan_WWW.splusname</code></strong><br/>
              Mehrere ID's im URL: 
               <div class="example-contents">
            <div class="phpcode">
                <code>
              client.php?f=<span style="color: #DD0000">function</span>&id[]=<span style="color: #DD0000">int</span>&id[]=<span style="color: #DD0000">int</span>&id[]=<span style="color: #DD0000">int</span>
                </code></div></div>
                
          </p>
      </dd>
   </dl>
 </div>
 <div class="refsect1 returnvalues" >
  <h3 class="title">Rückgabewerte</h3>
  <p class="para">
   Liefert ein Json-Objekt mit folgender Struktur:
    
    <p><strong>#1 <span class="function"><strong>Courses</strong></span></strong></p>
    <div class="example-contents screen">
        <div class="cdata"><pre>
{
    "version": Aktuelle Schnittstellenversion,
    "courses": [
        {
            "course": String Studiengangkürzel,
            "year": String Jahr,
            "labels": {
                "de": String deutscher Studiengangbezeichner,
                "en": String englischer Studiengangbezeichner
            },
            "semester": [
                String aktuell verfügbaren Semester
            ]
        },
        {
            ...
        }
    ]
}</pre></div>
    </div>          
    <p><strong>#2 <span class="function"><strong>Schedule</strong></span></strong></p>
    <div class="example-contents screen">
        <div class="cdata"><pre>
{
    "version": Aktuelle Schnittstellenversion,
    "schedule": [
        {
            "id": String Tabellen-ID,
            "label": String Vorlesungsbezeichner,
            "docent": String Dozentenname,
            "type": String Art der Vorlesung,
            "group": String Gruppe,
            "starttime": String Startzeitpunt der Vorlesung (HH:mm),
            "endtime": String Endzeitpunt der Vorlesung (HH:mm),
            "startdate": String Datum der ersten Vorlesung (dd.mm.yyyy),
            "enddate": String Datum der letzten Vorlesung (dd.mm.yyyy),
            "day": String Wochentag,
            "room": String Raum,
            "splusname": String SplusName,
			"comment": String Kommentar der Vorlesung,
            "sp": String Die SP Information
        },
        {
            ...
        }
    ]
}</pre></div>
    </div>
    <p><strong>#2.1 <span class="function"><strong>MySchedule</strong></span></strong></p>
    <div class="example-contents screen">
        <div class="cdata"><pre>
{
    "version": Aktuelle Schnittstellenversion,
    "myschedule": [
        {
            "id": String Tabellen-ID,
            "label": String Vorlesungsbezeichner,
            "docent": String Dozentenname,
            "type": String Art der Vorlesung,
            "group": String Gruppe,
            "starttime": String Startzeitpunt der Vorlesung (HH:mm),
            "endtime": String Endzeitpunt der Vorlesung (HH:mm),
            "startdate": String Datum der ersten Vorlesung (dd.mm.yyyy),
            "enddate": String Datum der letzten Vorlesung (dd.mm.yyyy),
            "day": String Wochentag,
            "room": String Raum,
            "splusname": String SplusName,
            "comment": String Kommentar der Vorlesung,
            "sp": String Die SP Information
        },
        {
            ...
        }
    ]
}</pre></div>
    </div>
    <p><strong>#3 <span class="function"><strong>Changes</strong></span></strong></p>
    <div class="example-contents screen">
        <div class="cdata"><pre>
{
    "version": Aktuelle Schnittstellenversion,
    "changes": [
        {
            "id": String Tabellen-ID,
            "label": String Vorlesungsbezeichner,
            "docent": String Dozentenname,
            "comment": String Kommentar,
            "reason": String Grund,
            "group": String Gruppe,
            "splusname": SplusName
            "original": {
                "day": String Wochentag der eigentlichen Vorlesung,
                "time": String Uhrzeit der eigentlichen Vorlesung (HH:mm),
                "date": String Datum der eigentlichen Vorlesung (dd.mm.yyyy),
                "room": String Raum der eigentlichen Vorlesung
            },
            "alternative": {
                "day": String Wochentag der Ersatzvorlesung,
                "time": String Uhrzeit der Ersatzvorlesung (HH:mm),
                "date": String Datum der Ersatzvorlesung (dd.mm.yyyy),
                "room": String Raum der Ersatzvorlesung
            }
        },
        {
            ...
        }
    ]
}</pre></div>
    </div>           
    <p><strong>#4 <span class="function"><strong>MSchedule</strong></span></strong></p>
    <div class="example-contents screen">
        <div class="cdata"><pre>
{
    "version": Aktuelle Schnittstellenversion,
    "changes": [        
        {
            "label": String Vorlesungsbezeichner,
            "docent": String Dozentenname,
            "type": String Art der Vorlesung,
            "group": String Gruppe,
            "starttime": String Startzeitpunt der Vorlesung (HH:mm),
            "endtime": String Endzeitpunt der Vorlesung (HH:mm),
            "startdate": String Datum der ersten Vorlesung (dd.mm.yyyy),
            "enddate": String Datum der letzten Vorlesung (dd.mm.yyyy),
            "day": String Wochentag,
            "room": String Raum,
            "changes": {
                "comment": String Kommentar zur Verlegung,
                "reason": "String Grund der Verlegung,
                "day": String Wochentag der Ersatzvorlesung,
                "time": String Uhrzeit der Ersatzvorlesung (HH:mm),
                "date": String Datum der Ersatzvorlesung (dd.mm.yyyy),
                "room": String Raum der Ersatzvorlesung
            }
        },
        {
            ...
        }
    ]
}</pre></div>
    </div>
    
  </p>
 </div>


 <div class="examples" >
     <h3 class="title">Beispiele</h3>
     <div class="example">
        <p><strong>#1 <span class="function"><strong>Courses</strong></span></strong></p>
         <div class="example-contents">
            <div class="phpcode">
                <code>
                    <span style="color: #000000">client.php?f=<span style="color: #DD0000">Courses</span>&tt=<span style="color: #DD0000">SS</span>
                </code>
            </div>
         </div>
         <div class="example-contents"><p>Das oben gezeigte Beispiel erzeugt folgende
                 Ausgabe:</p></div>
         <div class="example-contents screen">
             <div class="cdata"><pre>
{
    "version": Aktuelle Schnittstellenversion,
    "courses": [
        {
            "course": "BBB",
            "year": "2016",
            "labels": {
                "de": "Berufsbegleitender Bachelor Betriebswirtschaft",
                "en": "Business Administration (part-time)"
            },
            "semester": [
                "4",
                "5",
                "6",
                "8"
            ]
        },
        {
            ...
        }
    ]
}</pre></div>
         </div>
        
         <p><strong>#2 <span class="function"><strong>Schedule/MySchedule</strong></span></strong></p>
         <div class="example-contents">
            <div class="phpcode"><code><span style="color: #000000">
                client.php?f=<span style="color: #DD0000">Schedule</span>&stg=<span style="color: #DD0000">Inf</span>&sem=<span style="color: #DD0000">6</span>&tt=<span style="color: #DD0000">SS</span>
            </span></code></div>
             <div class="phpcode"><code><span style="color: #000000">
                client.php?f=<span style="color: #DD0000">MySchedule</span>&id[]=<span style="color: #DD0000">1332256</span>
            </span></code></div>
         </div>
         <div class="example-contents"><p>Das oben gezeigte Beispiel erzeugt folgende
                 Ausgabe:</p></div>
         <div class="example-contents screen">
             <div class="cdata"><pre>
{
    "version": Aktuelle Schnittstellenversion,
    "schedule": [
        {
            "id": "1332256",
            "label": "Technische Materialflusssysteme",
            "docent": "Prof. Dr. Valentin Plenk§§ Wolfgang Uschold",
            "type": "Vorlesung Fixzeit",
            "group": "",
            "starttime": "08:00",
            "endtime": "09:30",
            "startdate": "21.03.2016",
            "enddate": "19.12.2016",
            "day": "Montag",
            "room": "FA007",
            "splusname": "TMS§vplenk_2+wuschold_1%45646\/45648 $ 2",
            "comment": "(Beginn ab KW 20)",
            "sp": "-"
        },
        {
            ...
        }
    ]
}</pre></div>
         </div>
         <p><strong>#3 <span class="function"><strong>Changes</strong></span></strong></p>
         <div class="example-contents">
            <div class="phpcode"><code><span style="color: #000000">
                client.php?f=<span style="color: #DD0000">Changes</span>&stg=<span style="color: #DD0000">Inf</span>&sem=<span style="color: #DD0000">6</span>&tt=<span style="color: #DD0000">SS</span>
            </span></code></div>
         </div>
         <div class="example-contents"><p>Das oben gezeigte Beispiel erzeugt folgende
                 Ausgabe:</p></div>
         <div class="example-contents screen">
             <div class="cdata"><pre>
{
    "version": Aktuelle Schnittstellenversion,
    "changes": [
        {
            "id": "628026",
            "label": "Technische Materialflusssysteme",
            "docent": "Prof. Dr. Valentin Plenk§§ Wolfgang Uschold",
            "comment": "(Wing IT 4 + MT\/MT 6 + Inf 6)",
            "reason": "Fachlich",
            "group": "",
            "splusname": "TMS§vplenk_2+wuschold_1%45646\/45648 $ 2 Vertretung01",
            "original": {
                "day": "Montag",
                "time": "08:00",
                "date": "04.07.2016",
                "room": "FA007"
            },
            "alternative": {
                "day": "Freitag",
                "time": "11:30",
                "date": "08.07.2016",
                "room": "FA007"
            }
        },
        {
            ...
        }
    ]
}</pre></div>
         </div>
         <p><strong>#4 <span class="function"><strong>MSchedule</strong></span></strong></p>
         <div class="example-contents">
            <div class="phpcode"><code><span style="color: #000000">
                client.php?f=<span style="color: #DD0000">MSchedule</span>&stg=<span style="color: #DD0000">md</span>&sem=<span style="color: #DD0000">4</span>&tt=<span style="color: #DD0000">SS</span>
            </span></code></div>
         </div>
         <div class="example-contents"><p>Das oben gezeigte Beispiel erzeugt folgende
                 Ausgabe:</p></div>
         <div class="example-contents screen">
             <div class="cdata"><pre>
{
    "version": Aktuelle Schnittstellenversion,
    "changes": [        
        {
            "label": "Medien- und Designrecht",
            "docent": "Prof. Dr. Beatrix Weber",
            "type": "Vorlesung Fixzeit",
            "group": "",
            "starttime": "11:30",
            "endtime": "13:00",
            "startdate": "07.04.2016",
            "enddate": "15.12.2016",
            "day": "Donnerstag",
            "room": "Mueb_335",
            "changes": {
                "comment": "14-tägig (Beginn KW 12)",
                "reason": "Dienstlich",
                "day": "Freitag",
                "time": "11:30",
                "date": "24.06.2016",
                "room": "Mueb_335"
            }
        },
        {
            ...
        }
    ]
}</pre></div>
         </div>         
     </div>
 </div>
</div>
</div>
</section>
</div>
</body>
</html>