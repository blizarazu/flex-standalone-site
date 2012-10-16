--
-- Dumping data for table `preferences`
--

LOCK TABLES `preferences` WRITE;
/*!40000 ALTER TABLE `preferences` DISABLE KEYS */;
INSERT INTO `preferences` (`id`,`prefName`,`prefValue`) VALUES 
(1,'initialCredits','40'),
(2,'subtitleAdditionCredits','4'),
(3,'evaluationRequestCredits','10'),
(4,'evaluatedWithVideoCredits','20'),
(5,'videoSuggestCredits','2'),
(6,'dailyLoginCredits','0.5'),
(7,'evaluatedWithCommentCredits','1.5'),
(8,'evaluatedWithScoreCredits','20'),
(9,'subtitleTranslationCredits','1.5'),
(10,'uploadExerciseCredits','16'),
(11,'dbrevision','$Revision: 707 $'),
(12,'appRevision','3'),
(13,'trial.threshold','3'),
(14,'hashLength','20'),
(15,'hashChars','abcdefghijklmnopqrstuvwxyz0123456789-_'),
(18,'spinvox.language','en'),
(22,'spinvox.protocol','https'),
(24,'spinvox.port','443'),
(25,'spinvox.dev_url','dev.api.spinvox.com'),
(26,'spinvox.live_url','live.api.spinvox.com'),
(27,'spinvox.max_transcriptions','10'),
(28,'spinvox.max_requests','50'),
(31,'spinvox.max_duration','30'),
(32,'spinvox.dev_mode','true'),
(34,'positives_to_next_level','15'),
(35,'reports_to_delete','10'),
(38,'bwCheckMin','512'),
(39,'exerciseFolder','exercises'),
(40,'evaluationFolder','evaluations'),
(41,'responseFolder','responses'),
(42,'spinvox.language','fr'),
(43,'spinvox.language','de'),
(44,'spinvox.language','it'),
(45,'spinvox.language','pt'),
(46,'spinvox.language','es'),
(47,'minVideoRatingCount','10'),
(48,'reportCredit','2'),
(49,'web_domain','babelium'),
(50,'minExerciseDuration',15),
(51,'maxExerciseDuration',120),
(52,'minVideoEvalDuration',5),
(53,'maxFileSize',188743680);
/*!40000 ALTER TABLE `preferences` ENABLE KEYS */;
UNLOCK TABLES;


--
-- Dumping data for table `exercise_descriptor`
--

LOCK TABLES `exercise_descriptor` WRITE;
/*!40000 ALTER TABLE `exercise_descriptor` DISABLE KEYS */;
INSERT INTO exercise_descriptor (`id`,`level`,`type`,`number`) VALUES
(001,'A1','SI',01),
(002,'A1','SI',02),
(003,'A1','SI',03),
(004,'A1','SI',04),
(005,'A1','SI',05),
(006,'A1','SI',06),
(007,'A1','SI',07),
(008,'A1','SI',08),
(009,'A1','SP',01),
(010,'A1','SP',02),
(011,'A2','SI',01),
(012,'A2','SI',02),
(013,'A2','SI',03),
(014,'A2','SI',04),
(015,'A2','SI',05),
(016,'A2','SI',06),
(017,'A2','SI',07),
(018,'A2','SI',08),
(019,'A2','SI',09),
(020,'A2','SI',10),
(021,'A2','SI',11),
(022,'A2','SI',12),
(023,'A2','SP',01),
(024,'A2','SP',02),
(025,'A2','SP',03),
(026,'A2','SP',04),
(027,'A2','SP',05),
(028,'A2','SP',06),
(029,'B1','SI',01),
(030,'B1','SI',02),
(031,'B1','SI',03),
(032,'B1','SI',04),
(033,'B1','SI',05),
(034,'B1','SI',06),
(035,'B1','SI',07),
(036,'B1','SP',01),
(037,'B1','SP',02),
(038,'B1','SP',03),
(039,'B1','SP',04),
(040,'B1','SP',05),
(041,'B1','SP',06),
(042,'B2','SI',01),
(043,'B2','SI',02),
(044,'B2','SI',03),
(045,'B2','SI',04),
(046,'B2','SI',05),
(047,'B2','SI',06),
(048,'B2','SI',07),
(049,'B2','SP',01),
(050,'B2','SP',02),
(051,'B2','SP',03),
(052,'B2','SP',04),
(053,'B2','SP',05),
(054,'B2','SP',06),
(055,'C1','SI',01),
(056,'C1','SI',02),
(057,'C1','SI',03),
(058,'C1','SI',04),
(059,'C1','SP',01),
(060,'C1','SP',02),
(061,'C1','SP',03),
(062,'C1','SP',04),
(063,'C2','SI',01),
(064,'C2','SP',01),
(065,'C2','SP',02);
/*!40000 ALTER TABLE `exercise_descriptor` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `exercise_descriptor_i18n`
--

LOCK TABLES `exercise_descriptor_i18n` WRITE;
/*!40000 ALTER TABLE `exercise_descriptor_i18n` DISABLE KEYS */;
INSERT INTO exercise_descriptor_i18n (`fk_exercise_descriptor_id`,`locale`,`name`) VALUES
(001,'en_US','I can introduce somebody and use basic greetings and leave-taking expressions.'),
(002,'en_US','I can ask and answer simple questions, initiate and respond to simple statements in areas of immediate need or on very familiar topics.'),
(003,'en_US','I can make myself understood in a simple way but I am dependent on my partner being prepared to repeat more slowly and rephrase what I say and to help me to say what I want.'),
(004,'en_US','I can make simple purchases where pointing or other gestures can support what I say.'),
(005,'en_US','I can handle numbers, quantities, cost and time.'),
(006,'en_US','I can ask people for things and give people things.'),
(007,'en_US','I can ask people questions about where they live, people they know, things they have etc. and answer such questions addressed to me provided they are articulated slowly and clearly.'),
(008,'en_US','I can indicate time by such phrases as "next week", "last Friday", "in November", "three o’clock".'),
(009,'en_US','I can give personal information (address, telephone number, nationality, age, family, and hobbies).'),
(010,'en_US','I can describe where I live.'),
(011,'en_US','I can make simple transactions in shops, post offices or banks.'),
(012,'en_US','I can use public transport: buses, trains, and taxis, ask for basic information and buy tickets.'),
(013,'en_US','I can get simple information about travel.'),
(014,'en_US','I can order something to eat or drink.'),
(015,'en_US','I can make simple purchases by stating what I want and asking the price.'),
(016,'en_US','I can ask for and give directions referring to a map or plan.'),
(017,'en_US','I can ask how people are and react to news.'),
(018,'en_US','I can make and respond to invitations.'),
(019,'en_US','I can make and accept apologies.'),
(020,'en_US','I can say what I like and dislike.'),
(021,'en_US','I can discuss with other people what to do, where to go, and make arrangements to meet.'),
(022,'en_US','I can ask people questions about what they do at work and in free time, and answer such questions addressed to me.'),
(023,'en_US','I can describe myself, my family and other people.'),
(024,'en_US','I can describe where 1 live.'),
(025,'en_US','I can give short, basic descriptions of events.'),
(026,'en_US','I can describe my educational background, my present or most recent job.'),
(027,'en_US','I can describe my hobbies and interests in a simple way.'),
(028,'en_US','I can describe past experiences and personal experiences (e.g. the last weekend, my last holiday).'),
(029,'en_US','I can start, maintain and close simple face-to-face conversation on topics that are familiar or of personal interest.'),
(030,'en_US','I can maintain a conversation or discussion but may sometimes be difficult to follow when trying to say exactly what I would like to.'),
(031,'en_US','I can deal with most situations likely to arise when making travel arrangements through an agent or when actually travelling.'),
(032,'en_US','I can ask for and follow detailed directions.'),
(033,'en_US','I can express and respond to feelings such as surprise, happiness, sadness, interest and indifference.'),
(034,'en_US','I can give or seek personal views and opinions in an informal discussion with friends.'),
(035,'en_US','I can agree and disagree politely.'),
(036,'en_US','I can narrate a story.'),
(037,'en_US','I can give detailed accounts of experiences, describing feelings and reactions.'),
(038,'en_US','I can describe dreams, hopes and ambitions.'),
(039,'en_US','I can explain and give reasons for my plans, intentions and actions.'),
(040,'en_US','I can relate the plot of a book or film and describe my reactions.'),
(041,'en_US','I can paraphrase short written passages orally in a simple fashion, using the original text wording and ordering.'),
(042,'en_US','I can initiate, maintain and end discourse naturally with effective turn-taking.'),
(043,'en_US','I can exchange considerable quantities of detailed factual information on matters within my fields of interest.'),
(044,'en_US','I can convey degrees of emotion and highlight the personal significance of events and experiences.'),
(045,'en_US','I can engage in extended conversation in a clearly participatory fashion on most general topics.'),
(046,'en_US','I can account for and sustain my opinions in discussion by providing relevant explanations, arguments and comments.'),
(047,'en_US','I can help a discussion along on familiar ground confirming comprehension, inviting others in etc.'),
(048,'en_US','I can carry out a prepared interview, checking and confirming information, following up interesting replies.'),
(049,'en_US','I can give clear, detailed descriptions on a wide range of subjects related to my fields of interest.'),
(050,'en_US','I can understand and summarise orally short extracts from news items, interviews or documentaries containing opinions, argument and discussion.'),
(051,'en_US','I can understand and summarise orally the plot and sequence of events in an extract from a film or play.'),
(052,'en_US','I can construct a chain of reasoned argument, linking my ideas logically.'),
(053,'en_US','I can explain a viewpoint on a topical issue giving the advantages and disadvantages of various options.'),
(054,'en_US','I can speculate about causes, consequences, hypothetical situations.'),
(055,'en_US','I can keep up with an animated conversation between native speakers.'),
(056,'en_US','I can use the language fluently, accurately and effectively on a wide range of general, professional or academic topics.'),
(057,'en_US','I can use language flexibly and effectively for social purposes, including emotional, allusive and joking usage.'),
(058,'en_US','I can express my ideas and opinions clearly and precisely, and can present and respond to complex lines of reasoning convincingly.'),
(059,'en_US','I can give clear, detailed descriptions of complex subjects.'),
(060,'en_US','I can orally summarise long, demanding texts.'),
(061,'en_US','I can give an extended description or account of something, integrating themes, developing particular points and concluding appropriately.'),
(062,'en_US','I can give a clearly developed presentation on a subject in my fields of personal or professional interest, departing when necessary from the prepared text and following up spontaneously points raised by members of the audience.'),
(063,'en_US','I can take part effortlessly in all conversations and discussions with native speakers.'),
(064,'en_US','I can summarise orally information from different sources, reconstructing arguments and accounts in a coherent presentation.'),
(065,'en_US','I can present ideas and viewpoints in a very flexible manner in order to give emphasis, to differentiate and to eliminate ambiguity.'),

(001,'es_ES','Puedo presentar a alguien y utilizar expresiones sencillas de saludo y despedida.'),
(002,'es_ES','Puedo hacer y contestar preguntas sencillas. Construyo frases sencillas sobre asuntos de necesidad inmediata o muy familiares.'),
(003,'es_ES','Puedo hacerme entender de forma sencilla, pero necesito que mi interlocutor esté dispuesto a repetir más despacio, a reconstruir con otras palabras mis expresiones y a ayudarme a decir lo que quiero.'),
(004,'es_ES','Puedo hacer compras sencillas en situaciones donde señalar u otros gestos pueden apoyar lo que digo.'),
(005,'es_ES','Puedo manejar números, cantidades, precios y horas.'),
(006,'es_ES','Puedo pedir algo a alguien y dar lo que me piden.'),
(007,'es_ES','Puedo hacer preguntas personales (dónde vive una persona, qué gente conoce, qué cosas tiene, etc.) y contestar ese tipo de preguntas si se me hacen despacio y con claridad.'),
(008,'es_ES','Puedo hacer indicaciones temporales con frases como "la próxima semana", "el jueves pasado", "en noviembre", "las tres en punto".'),
(009,'es_ES','Puedo dar información personal (dirección, número de teléfono, nacionalidad, edad, familia y aficiones).'),
(010,'es_ES','Puedo describir el sitio donde vivo.'),
(011,'es_ES','Soy capaz de realizar operaciones sencillas en tiendas, oficinas de correos o bancos.'),
(012,'es_ES','Puedo desenvolverme en los transportes públicos (autobuses, trenes y taxis), pedir información básica y comprar billetes.'),
(013,'es_ES','Puedo obtener información sencilla sobre viajes.'),
(014,'es_ES','Puedo pedir algo de comer o beber.'),
(015,'es_ES','Puedo hacer compras sencillas explicando lo que quiero y preguntando el precio.'),
(016,'es_ES','Puedo preguntar y dar direcciones relacionadas con un mapa o plano.'),
(017,'es_ES','Puedo preguntar a los demás cómo se encuentran y reaccionar ante lo que me dicen.'),
(018,'es_ES','Puedo invitar y responder a invitaciones.'),
(019,'es_ES','Puedo disculparme y aceptar disculpas.'),
(020,'es_ES','Puedo decir lo que me gusta y lo que no me gusta.'),
(021,'es_ES','Puedo discutir con otras personas sobre qué hacer, dónde ir y cómo quedar más tarde.'),
(022,'es_ES','Puedo preguntar a otras personas qué hacen en el trabajo o en su tiempo libre, y contestar cuando se me hacen tales preguntas.'),
(023,'es_ES','Puedo describirme y describir a mi familia y a otras personas.'),
(024,'es_ES','Puedo describir el lugar donde vivo.'),
(025,'es_ES','Puedo describir acontecimientos de forma breve y básica.'),
(026,'es_ES','Puedo describir mi historial educativo, mi trabajo actual o lo último que he hecho.'),
(027,'es_ES','Puedo describir de forma sencilla mis aficiones e intereses.'),
(028,'es_ES','Puedo describir experiencias personales pasadas (sobre la semana anterior o sobre mis últimas vacaciones, por ejemplo).'),
(029,'es_ES','Puedo empezar, mantener y cerrar conversaciones sencillas cara a cara sobre temas que me son familiares o me interesan personalmente.'),
(030,'es_ES','Puedo mantener una conversación o una discusión, pero a veces me resulta difícil expresar exactamente lo que quiero decir.'),
(031,'es_ES','Puedo desenvolverme bien en la mayor parte de las situaciones que pueden presentarse al preparar un viaje en una agencia o durante el viaje.'),
(032,'es_ES','Puedo preguntar por una dirección y seguir las indicaciones detalladas que me dan.'),
(033,'es_ES','Puedo expresar sentimientos tales como sorpresa, felicidad, tristeza, interés o indiferencia y reaccionar adecuadamente cuando otras personas los expresan.'),
(034,'es_ES','Puedo intercambiar puntos de vista y opiniones personales en charlas informales.'),
(035,'es_ES','Puedo mostrar de forma educada mi acuerdo o desacuerdo.'),
(036,'es_ES','Puedo contar una historia.'),
(037,'es_ES','Puedo relatar experiencias con detalle, describiendo sentimientos y reacciones.'),
(038,'es_ES','Puedo describir sueños, esperanzas y ambiciones.'),
(039,'es_ES','Puedo explicar y detallar las razones de mis planes, intenciones y actos.'),
(040,'es_ES','Puedo contar el argumento de un libro o película y describir las reacciones que me ha provocado.'),
(041,'es_ES','Puedo reproducir de forma sencilla episodios escritos cortos, utilizando 1as palabras y el orden del texto original.'),
(042,'es_ES','Puedo iniciar, mantener y finalizar una conversación de forma natural respetando los turnos de palabra.'),
(043,'es_ES','Puedo intercambiar cantidades considerables de información relacionada con temas de mis áreas de interés.'),
(044,'es_ES','Puedo transmitir diversos grados de emoción y destacar la importancia que tienen para mí hechos y experiencias.'),
(045,'es_ES','Puedo participar de forma activa en una conversación extensa sobre la mayoría de los temas de interés más generales.'),
(046,'es_ES','Puedo exponer mis opiniones y defenderlas en una discusión, aportando explicaciones, razonamientos y comentarios relevantes.'),
(047,'es_ES','Puedo contribuir al desarrollo de una conversación en un ambiente conocido confirmando lo que comprendo, invitando a otros a participar, etc.'),
(048,'es_ES','Puedo llevar a cabo una entrevista preparada, preguntar si he entendido bien y profundizar en el caso de las respuestas que así lo requieran.'),
(049,'es_ES','Puedo hacer descripciones claras y detalladas sobre una gran variedad de temas relacionados con mi campo de interés.'),
(050,'es_ES','Puedo comprender y resumir oralmente fragmentos de noticias, entrevistas o documentales que contengan opiniones, argumentaciones y discusiones.'),
(051,'es_ES','Puedo comprender y resumir oralmente el argumento y desarrollo de los acontecimientos de un fragmento de película u obra de teatro.'),
(052,'es_ES','Puedo construir un razonamiento lógico enlazando adecuadamente las ideas.'),
(053,'es_ES','Puedo explicar un punto de vista sobre un tema cotidiano y exponer las ventajas y desventajas de diferentes opciones.'),
(054,'es_ES','Puedo formular suposiciones sobre causas y consecuencias y hablar de situaciones hipotéticas.'),
(055,'es_ES','Puedo desenvolverme en una conversación animada entre hablantes nativos.'),
(056,'es_ES','Puedo utilizar la lengua con soltura, precisión y eficacia en gran cantidad de temas generales, profesionales o académicos.'),
(057,'es_ES','Puedo utilizar la lengua con fines sociales de forma flexible y efectiva, incluyendo el uso emocional, alusivo y jocoso.'),
(058,'es_ES','Puedo expresar mis ideas y opiniones con claridad y precisión, y puedo exponer y rebatir de forma convincente líneas argumentales complejas.'),
(059,'es_ES','Puedo exponer de manera clara y detallada temas complejos.'),
(060,'es_ES','Puedo resumir de forma oral textos difíciles y extensos.'),
(061,'es_ES','Puedo hacer una descripción o relación extensa de algo, integrando diversos temas, desarrollando determinados puntos y concluyendo de forma apropiada.'),
(062,'es_ES','Puedo hacer una presentación desarrollada con claridad sobre un tema de mi especialidad o de interés personal, apartándome cuando sea necesario del texto preparado y extendiéndome sobre aspectos propuestos de forma espontánea por la audiencia.'),
(063,'es_ES','Puedo participar sin esfuerzo en todo tipo de conversaciones y discusiones con hablantes nativos.'),
(064,'es_ES','Puedo resumir de forma oral la información de distintas fuentes, y reconstruir argumentos y relatos en una presentación coherente.'),
(065,'es_ES','Puedo presentar ideas y puntos de vista de manera muy flexible con el fin de ponerlos de relieve, diferenciados y eliminar la ambigüedad.'),

(001,'eu_ES','Gai naiz norbait aurkezteko eta oinarrizko agurrak erabiltzeko ("kaixo", "agur").'),
(002,'eu_ES','Gai naiz galdera errazak egiteko eta erantzuteko; baieztapenak egiteko eta erantzuteko gai arrunt eta ezagunetan.'),
(003,'eu_ES','Gai naiz modu sinplean komunikatzeko, baina nire solaskidearen menpe nago, berak prest ego n behar baitu nik esandakoa astiro errepikatzeko, birformulatzeko edo nahi dudana esaten laguntzeko.'),
(004,'eu_ES','Erosketa errazak egin ditzaket keinuz lagunduta edo eskuaz seinalatuta.'),
(005,'eu_ES','Gai naiz zenbakiak, zenbatekoak, salneurriak eta orduak erabiltzeko.'),
(006,'eu_ES','Gai naiz jendeari gauzak eskatu eta emateko.'),
(007,'eu_ES','Gai naiz jendeari galdera sinpleak egiteko (non bizi diren, ezagutzen duten jendea, dauzkaten gauzak etab.); eta galdera hauek niri poliki eta argi eginez gero, gai naiz erantzuteko.'),
(008,'eu_ES','Gai naiz denbora adierazteko, "hurrengo astean", "joan den ostiralean", "azaroan", "hiruretan" bezalako esamoldeekin.'),
(009,'eu_ES','Gai naiz neure berri emateko: helbidea, telefono-zenbakia, herritartasuna, adina, familia, eta zaletasunak.'),
(010,'eu_ES','Gai naiz non bizi naizen deskribatzeko.'),
(011,'eu_ES','Gai naiz salerosketa sinpleak egiteko dendetan, posta bulegoan edo bankuan.'),
(012,'eu_ES','Gai naiz garraio publikoak erabiltzeko (autobusak, trenak eta taxiak), oinarrizko argibideak eskatzeko edo txartelak erosteko.'),
(013,'eu_ES','Gai naiz bidaiari buruzko informazio sinplea lortzeko.'),
(014,'eu_ES','Gai naiz edatekoa edo jatekoa eskatzeko.'),
(015,'eu_ES','Gai naiz erosketa sinpleak egiteko, zer nahi dudan esanez eta prezioa galdetuz.'),
(016,'eu_ES','Gai naiz mapa edo plano baten gainean bidea galdetzeko edota adierazteko.'),
(017,'eu_ES','Gai naiz jendea agurtzeko, nola dauden galdetzeko eta ematen dizkidaten berrien aurrean zerbait esateko.'),
(018,'eu_ES','Gai naiz gonbitak egiteko edo gonbitei erantzuteko.'),
(019,'eu_ES','Gai naiz aitzakiakjartzeko edota onartzeko.'),
(020,'eu_ES','Gai naiz zer gustatzen zaidan eta zer gustatzen ez zaidan esateko.'),
(021,'eu_ES','Gai naiz beste batzuekin zer egin eta nora joan eztabaidatzeko eta elkartzeko ordua eta tokia adosteko.'),
(022,'eu_ES','Gai naiz jendeari lanean eta aisialdian zer egiten duen galdetzeko; baita galdera horiei erantzuteko ere, niri egiten dizkidatenean.'),
(023,'eu_ES','Gai naiz neure burua, familia eta bestelako jendea ere deskribatzeko.'),
(024,'eu_ES','Gai naiz non bizi naizen deskribatzeko.'),
(025,'eu_ES','Gai naiz gertaeren deskribapen labur eta oinarrizkoak egiteko.'),
(026,'eu_ES','Gai naiz jasotako heziketa eta nire lehengo nahiz oraingo lana deskribatzeko.'),
(027,'eu_ES','Gai naiz nire zaletasunak eta interesak sinpleki deskribatzeko.'),
(028,'eu_ES','Gai naiz izandako pasadizoak eta esperientzia pertsonalak deskribatzeko (adib. azken asteburua, azken oporrak).'),
(029,'eu_ES','Gai naiz aurrez aurreko elkarrizketa sinpleak hasi, mantendu eta amaitzeko, gaia ezaguna edo interes pertsonalekoa denean.'),
(030,'eu_ES','Gai naiz elkarrizketan edo eztabaidan jarduteko, baina batzuetan zaila izan liteke esan nahi nukeena ulertzea.'),
(031,'eu_ES','Gai naiz txukun moldatzeko bidaia baten erreserba agentzian egiterakoan edo bidaian bertan gerta daitezkeen gorabeheretan.'),
(032,'eu_ES','Gai naiz helbide bati buruzko xehetasunak galdetzeko eta ematen dizkidaten azalpen xeheak jarraitzeko.'),
(033,'eu_ES','Gai naiz sentimenduak adierazteko, hala nola harridura, poza, tristura, jakin-mina edo ezaxola. Halaber, sentimenduok beste norbaitek adierazten dizkidanean, gai naiz erantzuna emateko.'),
(034,'eu_ES','Gai naiz neure ikuspuntu eta iritziak azaltzeko, lagunekin edo ezagunekin hitz egiten dudanean.'),
(035,'eu_ES','Gai naiz ados nagoela edo ez nagoela edukazioz adierazteko.'),
(036,'eu_ES','Gai naiz istorioak kontatzeko.'),
(037,'eu_ES','Gai naiz esperientziak zehatz azaltzeko, aldi berean nire sentimenduak eta erreakzioak deskribatuz.'),
(038,'eu_ES','Gai naiz nire ametsak, itxaropenak eta helburuak azaltzeko.'),
(039,'eu_ES','Gai naiz nire planak, asmoak eta ekintzak azaltzeko eta horien zergatiak emateko.'),
(040,'eu_ES','Gai naiz liburuen edo filmen argumentuak kontatzeko eta nire inpresioak deskribatzeko.'),
(041,'eu_ES','Gai naiz testu idatzien pasarte laburrak sinpleki ahoz kontatzeko, jatorrizko testuaren hitzak eta ordena erabiliz.'),
(042,'eu_ES','Gai naiz norbaitekin naturaltasunez hizketan hasi, jardun eta bukatzeko, hitz egiteko txandak hartuz eta emanez.'),
(043,'eu_ES','Gai naiz nire intereseko edo nire arloko informazio zehatz ugari beste norbaitekin trukatzeko.'),
(044,'eu_ES','Gai naiz emozio-graduak adierazteko eta esperientzietan edo gertaeretan axola zaidana azpimarratzeko.'),
(045,'eu_ES','Gai naiz iraupen jakineko elkarrizketan era aktiboan jarduteko, mintzagaiak interes orokorrekoak direnean.'),
(046,'eu_ES','Gai naiz eztabaidetan neure iritziak arrazoitu eta defenditzeko, horretarako argibideak, argudioak eta iruzkinak eskainiz.'),
(047,'eu_ES','Gai naiz elkarrizketari laguntzeko, gaia ezaguna denean, ulertzen dudala baieztatuz edo gainerako solaskideak hitz egitera gonbidatuz etab.'),
(048,'eu_ES','Gai naiz aldez aurretik prestatutako elkarrizketa bati aurre egiteko, ulertu dudana zuzena ote den baieztatzeko eskatuz eta erantzun batzuetan sakonduz.'),
(049,'eu_ES','Gai naiz deskribapen argi eta zehatzak egiteko nire gustuko arlo gehienen gainean.'),
(050,'eu_ES','Gai naiz aldizkari, elkarrizketa edo erreportajeetatik ateratako testu-zatiak irakurri eta ahoz laburtzeko, ikuspuntuak, argudioak eta eztabaidak islatuz.'),
(051,'eu_ES','Gai naiz film edo antzerki-Ianen argumentua eta gertakizunen sekuentzia ulertzeko eta ahoz laburtzeko.'),
(052,'eu_ES','Gai naiz arrazonamendu logikoak eraikitzeko eta nire ideiak uztartzeko.'),
(053,'eu_ES','Gai naiz arazoen gainean dudan ikuspuntua azaltzeko, aukeren arteko abantailak eta desabantailak agertuz.'),
(054,'eu_ES','Gai naiz gauzen zergatiez, ondorioez edota balizko egoerez ditudan irudipenak eta gogoetak azaltzeko.'),
(055,'eu_ES','Gai naiz jatorrizko hiztunen arteko elkarrizketa bizi-bizietan zuzenean parte hartzeko.'),
(056,'eu_ES','Gai naiz erraz, zuzen eta egoki hitz egiteko gai askoren gainean: gai orokorrak, lan-esparrukoak, akademikoak, zientifikoak.'),
(057,'eu_ES','Gai naiz jendartean modu malgu eta eraginkorrean hitz egiteko, sentimenduak azalduz, zeharkako aipamenak eginez edo umorea erabiliz.'),
(058,'eu_ES','Gai naiz nire ideiak eta iritziak eztabaidan argi eta zuzen emateko, konbentzitzeko moduan argudiatzeko, baita arrazonamendu zail eta bihurriei erantzuteko ere.'),
(059,'eu_ES','Gai naiz argi eta zehatz hitz egiteko, xehetasun eta guzti, gaiak zailak eta bihurriak badira ere.'),
(060,'eu_ES','Gai naiz testu luze eta bihurriak ahoz laburtzeko.'),
(061,'eu_ES','Gai naiz zerbait luze eta zabal azaltzeko, gaiak uztartuz, zenbait aspektu bereziki garatuz eta nire hizketaldia egoki amaituz.'),
(062,'eu_ES','Gai naiz lan-esparruko edo arlo pertsonaleko gaiak argi egituratuta aurkezteko, beharrezkoa denean, prestatutako testutik aldenduz eta entzuleen galderei bat-batean erantzunez.'),
(063,'eu_ES','Gai naiz jatorrizko hiztunekin edozein elkarrizketa edo eztabaidatan ahaleginik gabe parte hartzeko.'),
(064,'eu_ES','Gai naiz hainbat iturburutako informazioa ulertu eta ahoz laburtzeko, argudioak eta edukiak aurkezpen argi eta koherentean berreraikiz.'),
(065,'eu_ES','Gai naiz kontzeptuak eta ikuspuntuak malgu adierazteko, horrela informazioak nabarmenduz edo bereiztuz eta zalantzak ezabatuz.');
/*!40000 ALTER TABLE `exercise_descriptor_i18n` ENABLE KEYS */;
UNLOCK TABLES;
