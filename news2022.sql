-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Počítač: 127.0.0.1
-- Vytvořeno: Úte 18. říj 2022, 08:47
-- Verze serveru: 10.4.6-MariaDB
-- Verze PHP: 7.3.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databáze: `news2022`
--
CREATE DATABASE IF NOT EXISTS `news2022` DEFAULT CHARACTER SET utf8 COLLATE utf8_czech_ci;
USE `news2022`;

-- --------------------------------------------------------

--
-- Struktura tabulky `article`
--

CREATE TABLE `article` (
  `id` int(10) UNSIGNED NOT NULL,
  `author_id` int(11) NOT NULL,
  `title` varchar(150) COLLATE utf8_czech_ci NOT NULL,
  `perex` text COLLATE utf8_czech_ci NOT NULL,
  `text` text COLLATE utf8_czech_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Vypisuji data pro tabulku `article`
--

INSERT INTO `article` (`id`, `author_id`, `title`, `perex`, `text`, `created_at`) VALUES
(1, 1, 'Let\'s Encrypt zablokoval nebezpečnou validaci pomocí self-signed certifikátu ', 'Let\'s encrypt má první větší bezpečnostní problém. Za určitých okolností bylo možné získat certifikát i pro cizí doménové jméno. Jak zareagovali a jakým způsobem hodlají situaci řešit? ', '<h2>Jak funguje tls-sni-01</h2>\r\n\r\n<p>Validační metoda <code>tls-sni-01</code> je vynálezem tvůrců projektu autority Let\'s Encrypt. Spočívá ve vystavení <em>self-signed</em> certifikátu na neexistující doménové jméno (například <code>773c7d.13445a.acme.invalid</code>), které obsahuje ověřovací kód. Certifikační autorita při ověřování naváže s daným doménovým jménem TLS spojení a do hlavičky SNI vloží toto speciální jméno. K úspěšnému ověření dojde, pokud server odpoví certifikátem vystaveným na dané speciální jméno.</p><p>Validační metodu <code>tls-sni-01</code> používá především oficiální klient <a href=\"https://certbot.eff.org/\">Certbot</a>. Je výhodná pro automatizaci, protože vyžaduje minimální konfigurační zásahy do webserveru. Není ale jediná, Let\'s Encrypt podporuje také validaci <code>http-01</code>  spočívající ve vystavení souboru s určitým obsahem na určité cestě a <code>dns-01</code>, kde k ověření dochází umístněním TXT záznamu na doméně  <code>_acme-challenge.&lt;validované DNS jméno&gt;</code>.</p>\r\n\r\n<h2>Zranitelnost sdílených hostingů</h2>\r\n\r\n<p>Podle zjištění Franse Roséna existují provozovatelé sdílených webhostingů, pro které ověření metodou <code>tls-sni-01</code>  umožňuje získat cizí certifikát:</p>\r\n\r\n<ul>\r\n	<li>webhostingy různých zákazníků sdílí stejnou IP adresu</li>\r\n\r\n	<li>uživatelům je povoleno nahrát vlastní TLS certifikát bez kontroly, zda je vydán na doménové jméno držené daným uživatelem</li>\r\n</ul>\r\n\r\n<p>Kombinace těchto dvou okolností pak umožňuje získat TLS certifikát na libovolné doménové jméno hostované na stejné IP adrese. Mějme například dvojici webových prezentací, jednu na doméně <code>legit.example</code>, druhou na doméně <code>badguy.example</code>. První patří oběti, druhá útočníkovi, obě sdílí IP adresu. Útočník jednoduše požádá autoritu o certifikát na jméno <code>legit.example</code> a na výzvu autority vyrobí <em>self-signed</em> certifikát na autoritou požadované jméno, který nahraje jako certifikát pro jím ovládaný hosting <code>badguy.example</code>. Autorita se připojí na IP adresu oběti, která je shodná s IP adresou útočníka a požádá o speciální certifikát. Webserver ochotně vybere certifikát poskytnutý útočníkem, byť patří zcela jinému zákazníkovi.</p>\r\n\r\n<p>Zranitelnost tedy postihuje <strong>výlučně sdílené hostingy</strong>, pro které jsou splněny výše uvedené podmínky. Přitom už nezáleží na žádných dalších okolnostech. Zranitelnost stejným způsobem funguje i pro inovovanou variantu ověření <code>tls-sni-02</code>, která je součástí nového standardu protokolu ACME.</p>\r\n\r\n<h2>Reakce Let\'s Encrypt</h2>\r\n\r\n<p>V krátké době po zjištění incidentu byla validace metodou <code>tls-sni-01</code> vypnuta. I přesto, že nejde o nejoblíbenější metodu validace (tou je <code>http-01</code>), má své uživatele a velká část z nich nemůže zcela automaticky přejít na jiný druh validace. V plánu proto je validaci opět zprovoznit v momentě, kdy bude problém nějakým způsobem vyřešen nebo obejit.</p>\r\n\r\n<p>Lidé z ISRG, organizace stojící za projektem Let\'s Encrypt, se domnívají, že problém je možné zmírnit implementací silnějších kontrol na straně provozovatale webhostingu, tak aby si zákazník nemohl nahrát libovolný certifikát. Postižení provozovatelé jsou v kontaktu s ISRG a takové opravy by měly být brzy dostupné.</p>\r\n\r\n<p>Během následujících 48 hodin chce ISRG vytvořit seznam postižených webhostingů. Jakmile bude hotový, měla by být validace <code>tls-sni-01</code> znovu zprovozněna, s tím, že pro IP adresy na seznamu bude zablokována.</p>\r\n\r\n<p>Dalším krokem je pak vyvolání diskuze o budoucnosti validační metody v rámci komunity kolem Let\'s Encrypt a protokolu ACME. Je možné, že po zvážení všech pro a proti bude takováto validace prohlášena za zastaralou a její používání bude postupně utlumováno.</p>\r\n', '2018-01-11 11:08:38'),
(2, 2, 'Procesory Intel mají vážnou hardwarovou chybu, záplata výrazně snižuje výkon ', 'V procesorech Intel se nachází závažná bezpečnostní chyba, kterou nelze zcela opravit jinak než na úrovni hardwaru. Patche pro operační systém snižují výkon CPU až o desítky procent a problém se netýká jen Linuxu, ale i Windows. ', '<p>AMD stále tvrdí, že její CPU nejsou postižena (tedy přesněji řečeno, že nejde ani o zásadní, ani o obecný problém, viz <a href=\"http://www.amd.com/en/corporate/speculative-execution\">vyjádření společnosti</a>). Linus Torvalds mezitím do jádra <a href=\"https://www.phoronix.com/scan.php?page=news_item&amp;px=Linux-Tip-Git-Disable-x86-PTI\">začlenil patch</a>, který vypíná ochranu proti této chybě, tedy Page Table Isolation, pro CPU AMD. Google však naopak tvrdí, že postižena jsou i CPU ARM a AMD, nicméně blíže nic neupřesňuje (může jít tedy jen o určité architektury). Na <a href=\"https://spectreattack.com/\">webu Meltdown and Spectre</a> se hovoří o tom, že Meltdown postihuje prakticky všechna CPU od roku 1995 (kromě Intel Itanium a Atomů z doby před 2013). U Spectre je již ověřeno, že postihuje i CPU ARM a AMD.</p>\r\n\r\n<p>Bližší detailní informace shrnují dokumenty odkazované v <a href=\"https://spectreattack.com/\">dolní části webu Meltdown and Spectre</a> Google uvádí svá zjištění na <a href=\"https://googleprojectzero.blogspot.cz/2018/01/reading-privileged-memory-with-side.html\">webu týmu Zero</a>, resp. <a href=\"https://security.googleblog.com/2018/01/todays-cpu-vulnerability-what-you-need.html\">svém bezpečnostním blogu</a>.</p>\r\n\r\n<p>Úvodem nutno podotknout, že toto není <a href=\"https://www.root.cz/clanky/minix-je-zrejme-nejrozsirenejsim-systemem-je-ukryty-v-procesorech-intel/\">další článek o Intel Management Engine</a>. Jde o zcela jiný problém, pro který je v jádru 4.15 k dispozici sada opravných patchů, které byly/jsou/budou backportovány i do řad 4.14 (aktuální stabilní) a 4.9 (aktuální LTS). Podobnou věc implementují i Windows 10, v Microsoftu se na tom už <a href=\"https://twitter.com/aionescu/status/930412525111296000\">několik týdnů pracuje</a>.</p>\r\n\r\n<h2>Špatná implementace u Intelu</h2>\r\n\r\n<p>Procesory Intel totiž obsahují chybu implementace TLB (Translation Lookaside Buffer, součást CPU s nemalým dopadem na výkon), která potenciálně umožňuje útočníkovi dostat se k datům, ke kterým nemá daný uživatel systému oprávnění. Řečeno jinak: „útočník“ se může z jedné virtuální mašiny dostat k datům v paměti jiné virtuální mašiny. Problém se týká v podstatě všech CPU z posledních generací u Intelu, což mimo jiné znamená, že z něj plyne i teoretická napadnutelnost všech cloudových služeb využívajících CPU Intel (například Amazon EC2, Google Compute Engine, Microsoft Azure) či jakýchkoli jiných strojů.</p>\r\n\r\n<p>Řešení v softwarové podobě existuje, je na Linuxu implementováno jako <a href=\"https://en.wikipedia.org/wiki/Kernel_page-table_isolation\">Page Table Isolation</a>, ale představuje tak velkou zátěž z hlediska přerušení a systémových volání, že při reálném použití dochází k propadu výkonu CPU o jednotky až desítky procent. Řešení totiž spočívá v tom, že pokud program chce po jádru systému data z jeho paměti, musí nyní (patřičně opatchovaný) kernel nejprve smazat TLB cache.</p>\r\n\r\n<h2>Jak moc velký problém to je?</h2>\r\n\r\n<p>Detailní popis chyby není z pochopitelných důvodů zatím k dispozici, ale můžeme usuzovat z několika indicií. Tou první je ticho po pěšině, které se Intel snažil držet, podobně jako u Management Engine. To obvykle není dobré znamení. Tím druhým je, že změny do linuxového jádra připutovaly v rychlém sledu a dokonce jsou backportovány do starších verzí, včetně LTS.</p>\r\n\r\n<p>Úpravy existují i za cenu velké ztráty výkonu, takže je jasné, že bezpečnost (resp. závažnost problému) zde má o hodně vyšší prioritu. A v neposlední řadě s ohledem na to, že od loňského podzimu na věci pracuje i Microsoft pro Windows 10 s NT kernelem, lze to vnímat jako potvrzení hardwarové chyby.</p>\r\n\r\n<p>Objevilo se více informací o míře propadu výkonu po aplikaci patchů. <a href=\"https://www.techpowerup.com/240174/intel-secretly-firefighting-a-major-cpu-bug-affecting-datacenters\">Obecně se uvádí</a> propad na úrovni 30 až 35 %, <a href=\"https://www.phoronix.com/scan.php?page=article&amp;item=linux-415-x86pti&amp;num=1\">Phoronix provedl vlastní rozsáhlejší měření</a>. Z nich vyplynulo, že kupříkladu hry či komprese videa nejsou prakticky vůbec penalizovány. Nicméně dopady v I/O operacích, kompilačních testech či databázových (PostreSQL) jsou hodně velké.</p>\r\n\r\n<h2>AMD se to netýká</h2>\r\n\r\n<p>Důležité pro budoucí vývoj je to, že celý problém není dán návrhem x86 architektury jako takové (či nějaké pozdější instrukční sady x86 procesorů), ale konkrétní implementací konkrétní funkcionality tak, jak ji Intel ve svých CPU realizoval. Konkurenční AMD je tedy z obliga, jejích CPU se problém netýká, a platí to jak pro serverové Opterony, tak obecně pro procesory architektur Ryzen, Threadripper a EPYC.</p>\r\n\r\n<p>Pikantní ale je, že patche na tuto chybu mají velký výkonnostní dopad i na strojích s AMD CPU. Za vše totiž může aktivace <code>X86_BUG_CPU_INSECURE</code>, která vede k použití kódu, který neustále maže TLB. Toto označení je nyní aktivní pro všechny x86 CPU jako bezpečnostní opatření. AMD již <a href=\"https://lkml.org/lkml/2017/12/27/2\">řeší jeho odstranění pro svá CPU</a>.</p>\r\n\r\n<h2>Souvislosti a důsledky budou zajímavé</h2>\r\n\r\n<p>Dovolte mi nyní volněji dosadit celou věc do souvislostí. Máme zde tedy nyní procesory Intel, u kterých se pro několik posledních generací ví o dvou velkých problémech. Těmi generacemi myslím cokoli od Sandy Bridge výše (o Core 2 se už nemá smysl příliš bavit). V různých verzích x86 CPU architektur Intelu se nachází různé verze Intel Management Engine, tedy vlastní malý běžící „počítač“, aktuálně používající x86 CPU s OS Minix – to samo o sobě je potenciálně velký problém, nicméně probrali jsme ho před časem <a href=\"https://www.root.cz/clanky/minix-je-zrejme-nejrozsirenejsim-systemem-je-ukryty-v-procesorech-intel/\">v samostatném článku</a>.</p>\r\n\r\n<p>Intel si zkrátka loni svoji reputaci vůbec nevylepšil a nepřispívá tomu ani neustálé odkládání nových výrobních procesů (indikující neschopnost přivést 10nm výrobu x86 CPU k světu – a dokládají to i nejnovější neoficiální data). A nyní přichází další rána: všechny x86 procesory Intel jsou prokazatelně nebezpečné a není možné s tím nic udělat bez velké výkonnostní penalizace.</p>\r\n\r\n<p>A právě ta penalizace je věc, která Intelu dle mého ubírá obchody (vysvětlím za chvíli). Penalizaci na úrovni desítek procent si Intel mohl dovolit v době, kdy neměl konkurenci, tj. kdy AMD měla na trhu mizerné procesory typu Bulldozer/Piledriver (AMD FX 8 a 9). Nyní je situace zcela jiná, AMD má na trhu vynikající procesory od desktopů (Ryzen), přes hi-end desktopy (Threadripper) až po servery (EPYC). Intel prakticky není schopen jí konkurovat, maximálně dokáže oproti 16jádrovéhu Threadripperu s cenovkou 25 tisíc Kč postavit o trošku výkonnější 18jádrové Core i9–7980XE s cenovkou 48 tisíc Kč.</p>\r\n\r\n<p>Sousloví „o trošku výkonnější“ si ale můžeme dnes škrtnout, protože záplaty na hardwarovou chybu v procesorech Intel ubírají výrazně výkon jak na Linuxu, tak na Windows a není v moci Intelu s tím cokoli udělat (avšak nutno zdůraznit, že se to týká spíše určitých typů aplikací). Pokud tuto argumentaci přeženu, tak by na základě známých skutečností mělo být možné tvrdit, že AMD má momentálně na desktopovém trhu prokazatelně rychlejší procesor (Threadripper 1950X) za zhruba poloviční cenu oproti konkurenčnímu model Core i9–7980XE. A podobné lze tvrdit i o serverových procesorech, kde jsou cenové rozdíly mnohdy ještě zajímavější.</p>\r\n\r\n<p>Pokud byl rok 2017 z hlediska trhu x86 CPU první po letech stagnace opravdu zajímavým rokem, tak to byl teprve slaboučký odvar toho, co nás čeká letos. Už několik let tvrdím, že Intel usnul na vavřínech, stačilo mu oproti skomírající AMD pouze udržovat status quo a inovovat jen mírně. Nyní to za svůj přístup schytá a může si za to vlastně sám.</p>\r\n\r\n<p>Dlužno připomenout, že tento problém s implementací TLB cache není první. V roce 2008 <a href=\"https://www.anandtech.com/show/2477/2\">měly první AMD Phenomy též chybu v této části CPU</a> a záplata vedla též k propadům výkonu. Z hlediska architektury x86 CPU tomu tam asi bude u TLB vždy.</p>\r\n', '2018-01-11 10:51:06');

-- --------------------------------------------------------

--
-- Struktura tabulky `article_category`
--

CREATE TABLE `article_category` (
  `article_id` int(10) UNSIGNED NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Vypisuji data pro tabulku `article_category`
--

INSERT INTO `article_category` (`article_id`, `category_id`) VALUES
(1, 1),
(2, 1),
(2, 2);

-- --------------------------------------------------------

--
-- Struktura tabulky `author`
--

CREATE TABLE `author` (
  `id` int(11) NOT NULL,
  `name` varchar(20) COLLATE utf8_czech_ci NOT NULL,
  `surname` varchar(50) COLLATE utf8_czech_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Vypisuji data pro tabulku `author`
--

INSERT INTO `author` (`id`, `name`, `surname`) VALUES
(1, 'Karel', 'Vágner'),
(2, 'Eliška', 'Mladá');

-- --------------------------------------------------------

--
-- Struktura tabulky `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8_czech_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Vypisuji data pro tabulku `category`
--

INSERT INTO `category` (`id`, `name`) VALUES
(1, 'Bezpečnost'),
(2, 'Hardware');

--
-- Klíče pro exportované tabulky
--

--
-- Klíče pro tabulku `article`
--
ALTER TABLE `article`
  ADD PRIMARY KEY (`id`),
  ADD KEY `author_id` (`author_id`);

--
-- Klíče pro tabulku `article_category`
--
ALTER TABLE `article_category`
  ADD PRIMARY KEY (`article_id`,`category_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Klíče pro tabulku `author`
--
ALTER TABLE `author`
  ADD PRIMARY KEY (`id`);

--
-- Klíče pro tabulku `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pro tabulky
--

--
-- AUTO_INCREMENT pro tabulku `article`
--
ALTER TABLE `article`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pro tabulku `author`
--
ALTER TABLE `author`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pro tabulku `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Omezení pro exportované tabulky
--

--
-- Omezení pro tabulku `article`
--
ALTER TABLE `article`
  ADD CONSTRAINT `article_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `author` (`id`);

--
-- Omezení pro tabulku `article_category`
--
ALTER TABLE `article_category`
  ADD CONSTRAINT `article_category_ibfk_1` FOREIGN KEY (`article_id`) REFERENCES `article` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `article_category_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
