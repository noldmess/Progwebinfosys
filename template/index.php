 
   <div class="hero-unit">
 
	<ol>
		<li><h3>Aufgabe 1</h3>
			<ol>
			<li>Server einrichten</li>
			<li>homapage  anlegen</li>
			</ol>
		</li>
		<li><h3>Aufgabe 2</h3>
			<ol>
			<li>Wiki mit Sessions</li>
			</ol>
		</li>
        <li><h3><a href="/aufgabe3/">Aufgabe 3</a></h3>
			<ol>
			<li>Wiki mit MySQL-Datenbank</li>
			</ol>
		</li>
        <li><h3><a href="/aufgabe4/">Aufgabe 4</a></h3>
			<ol>
			<li>Paginator bei Suche als auch restlichen Liste</li>
            <li>Generator: <a href="/aufgabe4/wiki/generator/1000/">generate 1000</a> <br />
            	usage: 138.232.66.90/aufgabe4/wiki/generator/x/ -> x steht für die Anzahl der zu erzeugenden Artikeln
            </li>
            <li>Linklisten</li>
			</ol>
		</li>
        <li><h3><a href="/aufgabe5/">Aufgabe 5</a></h3>
			<ol>
			<li>URL-Rewriting</li>
            <li>Imageupload bei Artikeln</li>
            <li>
            	Usermanagement
            	<br>
            	Neuer User durch einloggen wird automatisch erstellt!
            </li>
            <li>Angepasster Generator: <a href="/aufgabe5/wiki/generator/1000/">generate 1000</a> <br />
            	usage: 138.232.66.90/aufgabe5/wiki/generator/x/ -> x steht für die Anzahl der zu erzeugenden Artikeln
            </li>
			</ol>
		</li>
        <li><h3>Aufgabe 6</h3>
			<ol>
			<li>Zend installieren</li>
            <li>Rock-paper-scissors-lizard-Spock implementieren</li>
			</ol>
		</li>
       		<li><h3><a href="/zend1/">Aufgabe 7</a></h3>
			<ol>
				<li>ZF DB-Abstraktion</li>
				<li>Email Versand, ZF, SMTP: smtp.uibk.ac.at</li>
				<li>Revanche starten nach Spiel</li>
				<li>Highscore Liste auf Startseite</li>
				<li>Formular&uuml;berpr&uuml;fung mit JavaScript ohne JS-lib!</li>
				<li>Usability: Erkl&auml;rungen, Waffen-Auswahl &uuml;ber Bilder, CSS/Design</li>
				<li>Wiedererkennung per Cookie -> Vorausf&uuml;llen der eigenen Daten</li>
				<li>Testspiele: 10 Personen -> Feedback sammeln und dokumentieren</li>
			</ol>
		</li>
		<li><h3><a href="/zend2/">Aufgabe 8</a></h3>
			<ol>
				<li>Single Page Application (kein Reload der Seite) mit jQuery</li>
				<li>HTML wird nicht mehr vom Server dynamisch generiert</li>
				<li>Anzeige der Highscores mit automatischen Reload (alle 30 Sekunden) in der Single Page Application</li>
				<li>Gesamte Kommunikation mit dem Server &uuml;ber AJAX (jQuery)</li>
				<li>Installation MongoDB am Server</li>
				<li>Adaptierung Speicherung der Spiele von MySQL nach MongoDB</li>
				<li>Eingabe von Textnachrichten: beide Spieler k&ouml;nnen bei jedem Zug eine Textnachricht f&uuml;r den anderen Spieler setzen. Die zwei Nachrichten sollen jeweils zusammen mit dem Spiel in der DB gespeichert werden.</li>
			</ol>
		</li>
		<li><h3><a href="/zend3/">Aufgabe 9</a></h3>
			<ol>
				<li>Single Page App mit einer index.html fürs Game + Homeseite + Highscoreseite</li>
				<li>Client-Routen über Anchor #</li>
				<li>Usabilityverbesserungen, im besonderen:
					<ol>
						<li>
							Feedback bei AJAX (Loading, Sending, etc...)
						</li>
						<li>
							Beschreibung zu Spiel Ablauf (Emails + Web)
						</li>
						<li>
							Einfache Sprache
						</li>
					</ol>
				</li>
				<li>
					User-Tests (min. 5 Benutzer_innen, Feedback protokollieren) <a href="/feedback2.txt">Feedback</a>
				</li>
			</ol>
		</li>
		<li><h3><a class="node" href="/">Aufgabe 10</a></h3>
			<ol>
				<li>Chat Server unter Verwendung von Node.js und Socket.IO</li>
				<li>Web-basiert, Kommunikation zwischen JS Client (jQuery) und Server (Node.js) &uuml;ber Websockets (Socket.IO)</li>
				<li>Benutzer werden direkt beim Besuch der Webseite mit dem Chatserver verbunden und mit einem automatisch generierten Benutzername beim Chatserver angemeldet</li>
				<li>Danach haben sie die MÃ¶glichkeit Nachrichten einzugeben, die an alle angemeldeten Benutzer weitergeleitet werden</li>
				<li>Folgende Kommandos sollen - neben dem Versand von Nachrichten - realisiert werden:</li>
					<ol>
						<li>/name:myname - &Auml;ndert den Namen des aktuellen Benutzers auf myname</li>
						<li>/super:myname - der Benutzer myname erh&auml;lt Superrechte</li>
						<li>/kick:myname - Kickt den Benutzer myname aus dem Chatserver und schliesst damit die Verbindung</li>
						<li>/quit - Beendet die Verbindung zum Server</li>
						<li>/users - Zeigt alle Benutzernamen an, die derzeit mit dem Chatserver verbunden sind (Wurde als konstantes Fenster realisiert)</li>
						<li>/topic:mytopic - &Auml;ndert das Chattopic auf mytopic</li>
						<li>/topic - zeigt das aktuelle Chattopic an</li>
						<li>F&uuml;r folgende Befehle sind Superrechte n&ouml;tig: super, kick, topic:</li>
					</ol>
			</ol>
		</li>         
	</ol>
	</div>
