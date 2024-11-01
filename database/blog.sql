-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : jeu. 31 oct. 2024 à 20:19
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `blog`
--

-- --------------------------------------------------------

--
-- Structure de la table `article`
--

CREATE TABLE `article` (
  `id_article` int(4) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` varchar(4000) NOT NULL,
  `id_author` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `article`
--

INSERT INTO `article` (`id_article`, `title`, `description`, `id_author`) VALUES
(14, 'Les Secrets de l\'Endurance en Course à Pied', 'Dans le monde de la course à pied, l\'endurance est la clé de la performance et du plaisir de courir sur de longues distances. Que vous soyez débutant ou expérimenté, développer votre endurance vous permettra de courir plus longtemps, de mieux gérer votre rythme et de minimiser le risque de blessure. Voici quelques conseils essentiels pour renforcer votre endurance et profiter de chaque course !\r\n\r\n1. Trouvez Votre Rythme Idéal\r\nPour améliorer son endurance, il est important de trouver un rythme confortable, ni trop rapide, ni trop lent. Concentrez-vous sur la régularité, plutôt que sur la vitesse. En effet, courir trop vite au début épuise rapidement l\'énergie et limite la durée de l\'effort. Pour bien débuter, adoptez une allure qui vous permet de respirer sans difficulté. Vous devriez être capable de tenir une conversation sans haleter. Cela vous aidera à maintenir l\'effort sur une période prolongée.\r\n\r\n2. Augmentez Graduellement la Durée de Vos Sorties\r\nL’une des meilleures façons de progresser est d’augmenter progressivement la durée de vos sorties. Par exemple, ajoutez 5 à 10 minutes à chaque session hebdomadaire pour habituer votre corps à des distances plus longues. Un bon repère est de ne pas augmenter le kilométrage de plus de 10 % chaque semaine pour éviter les blessures. En peu de temps, vous constaterez que vous courez plus longtemps et plus facilement !\r\n\r\n3. L\'Importance de la Régularité dans l\'Entraînement\r\nCourir régulièrement, même sur des distances courtes, est essentiel pour développer l\'endurance. La régularité renforce le système cardiovasculaire et habitue le corps à l\'effort. Essayez de vous entraîner au moins trois fois par semaine, en alternant entre des courses longues, des courses plus rapides, et des sorties de récupération. La variation dans les entraînements aide à maintenir la motivation et à éviter la monotonie.\r\n\r\n4. Intégrez des Entraînements en Fractionné\r\nLe fractionné consiste à alterner entre des phases rapides et des phases de récupération lors de la course. Par exemple, courez à une vitesse élevée pendant 30 secondes, puis récupérez pendant 1 minute à un rythme plus lent. Ce type d’entraînement permet d’améliorer l’endurance, car il pousse votre corps à tolérer des intensités variées. Il est recommandé de faire des séances de fractionné une fois par semaine pour de meilleurs résultats.\r\n\r\n5. N\'oubliez pas l\'Alimentation et l\'Hydratation\r\nPour courir sur de longues distances, votre corps a besoin de carburant. Avant une course, privilégiez des aliments riches en glucides complexes (comme les pâtes complètes, le riz brun ou les légumes) pour vous fournir une énergie durable. Durant la course, pensez à boire régulièrement de petites quantités d’eau pour éviter la déshydratation, surtout si la séance dure plus de 30 minutes. Après l’effort, une collation riche en protéines aidera vos muscles à bien récupérer.\r\n\r\n6. Privilégiez le Repos et la Récupération\r\nLe repos est une composante essentielle de l’endurance, car c’est durant la récupération que votre corps se renforce. Accordez-vous des jours de repos, surtout après des entraînements intenses. Une bonne nuit de sommeil aide également votre corps à récupérer et à être en forme pour les prochaines séances. En incluant des étirements et de légers exercices de récupération, vous minimiserez les risques de blessures et améliorerez votre confort lors des courses longues.\r\n\r\n7. La Progression Patiente : Écoutez Votre Corps\r\nL\'endurance se construit sur le long terme, il est donc essentiel d\'écouter votre corps pour progresser sans risques. Si vous ressentez des douleurs, accordez-vous une pause. Pousser votre corps trop loin, trop vite, peut entraîner des blessures qui pourraient freiner votre progression. Écouter votre corps vous aidera à trouver le juste équilibre entre effort et repos.', 2),
(15, 'Top 10 des Entraînements pour Booster votre Musculation', 'La musculation est une discipline qui permet non seulement de développer la force et la masse musculaire, mais aussi d’améliorer l’équilibre, l’endurance et le bien-être général. Que vous soyez débutant ou confirmé, un bon programme d’entraînement vous aidera à optimiser vos résultats. Voici 10 exercices essentiels pour un programme complet de musculation, couvrant tous les principaux groupes musculaires.\r\n\r\n1. Le Squat\r\nLe squat est un exercice fondamental pour les jambes et les fessiers. Il sollicite les quadriceps, les ischio-jambiers et les fessiers, tout en faisant travailler le bas du dos et les abdominaux pour stabiliser le mouvement. Pour bien exécuter cet exercice, gardez le dos droit, descendez jusqu\'à ce que vos cuisses soient parallèles au sol, et remontez en poussant sur les talons.\r\n\r\n2. Le Développé Couché (Bench Press)\r\nC’est l’exercice de base pour développer la poitrine, les épaules et les triceps. Allongé sur un banc, prenez une barre ou des haltères et poussez le poids au-dessus de votre poitrine. Assurez-vous de bien contrôler la descente et de maintenir les omoplates serrées pour une meilleure stabilité.\r\n\r\n3. Le Soulevé de Terre (Deadlift)\r\nCet exercice complet sollicite presque tout le corps : jambes, dos, fessiers, abdominaux et avant-bras. Le soulevé de terre améliore la force et la coordination. Pour l’exécuter, gardez le dos droit, les pieds écartés à la largeur des épaules, et soulevez la barre en poussant avec les jambes tout en engageant les hanches.\r\n\r\n4. Les Tractions (Pull-Ups)\r\nLes tractions sont idéales pour travailler les muscles du dos et les biceps. Elles renforcent également les avant-bras et les abdominaux. Placez-vous sous une barre, attrapez-la avec les paumes vers l’avant, et soulevez-vous jusqu\'à ce que votre menton passe au-dessus de la barre. Pour débuter, vous pouvez utiliser une assistance avec une machine ou un élastique.\r\n\r\n5. Les Fentes (Lunges)\r\nLes fentes sont excellentes pour renforcer les quadriceps, les fessiers et les ischio-jambiers. Avancez une jambe et descendez jusqu\'à ce que le genou de la jambe arrière touche presque le sol. Cet exercice améliore également l\'équilibre et la stabilité. Vous pouvez le faire avec des haltères pour plus de résistance.\r\n\r\n6. Le Rowing avec Barre (Barbell Row)\r\nCet exercice sollicite les muscles du dos, en particulier les trapèzes et les rhomboïdes. En tenant une barre, penchez-vous légèrement en avant avec un dos droit et tirez la barre vers votre abdomen. Maintenez les coudes près du corps pour éviter de solliciter les épaules.\r\n\r\n7. Le Développé Militaire (Overhead Press)\r\nLe développé militaire est un excellent exercice pour les épaules et les triceps. Il se pratique debout ou assis, avec une barre ou des haltères. Poussez la barre au-dessus de votre tête en gardant le dos droit et les abdominaux contractés pour stabiliser le mouvement. Cet exercice développe aussi la coordination et l’équilibre.\r\n\r\n8. Les Crunchs et Variantes pour les Abdominaux\r\nPour travailler les abdominaux, les crunchs restent un classique efficace. Allongé sur le dos, contractez les abdominaux pour relever le buste sans tirer sur la nuque. Vous pouvez varier avec des crunchs obliques ou des exercices comme la planche pour travailler l’ensemble de la ceinture abdominale.\r\n\r\n9. Les Dips\r\nLes dips sont un excellent exercice pour travailler les triceps, la poitrine et les épaules. Placez vos mains sur une barre parallèle, pliez les coudes et abaissez votre corps lentement, puis poussez pour revenir en position initiale. Cet exercice peut être difficile au début, mais il est très efficace pour tonifier le haut du corps.\r\n\r\n10. Le Hip Thrust pour les Fessiers\r\nLe hip thrust est idéal pour cibler les fessiers. Assis par terre avec les épaules appuyées contre un banc, placez une barre sur vos hanches et poussez en levant les hanches vers le plafond. Cet exercice est excellent pour le renforcement des fessiers et améliore également la stabilité du bassin.', 2),
(16, 'Les Tendances en Esports pour l\'Année 2024', 'L\'univers des esports continue de croître de manière fulgurante et attire un public de plus en plus large, allant des fans de jeux vidéo aux marques globales. L\'année 2024 s\'annonce comme une année charnière avec de nouvelles tendances, des innovations technologiques, et des événements d\'ampleur mondiale. Dans cet article, découvrez les principales tendances et les nouveaux enjeux qui marqueront le monde de l’esport cette année.\r\n\r\n1. L’Ascension des Jeux de Réalité Virtuelle (VR)\r\nLa réalité virtuelle transforme peu à peu l’expérience de jeu, et en 2024, elle prend une place plus importante dans l’esport. Les jeux VR permettent aux joueurs de s\'immerger totalement dans l\'univers du jeu et offrent un gameplay plus dynamique et engageant. De nouveaux tournois VR voient le jour, surtout pour des titres comme Echo VR et Onward, attirant à la fois des spectateurs et des compétiteurs cherchant de nouvelles sensations. Bien que la VR en soit encore à ses débuts dans le monde de l’esport, les progrès technologiques permettent des jeux de plus en plus réalistes et accessibles.\r\n\r\n2. La Croissance des Jeux Mobiles dans les Compétitions\r\nLes jeux mobiles ont pris une ampleur impressionnante dans le secteur de l’esport, avec des titres comme PUBG Mobile, Mobile Legends, et Free Fire dominant les compétitions. La portabilité des appareils mobiles permet aux joueurs de s’entraîner et de jouer n’importe où, contribuant à une démocratisation de l’esport. En 2024, on assiste à une multiplication des tournois mobiles, notamment en Asie où ces compétitions attirent des millions de spectateurs en ligne et sur place.\r\n\r\n3. L’Intégration de l’Intelligence Artificielle dans le Coaching\r\nL\'intelligence artificielle joue désormais un rôle majeur dans l\'analyse et l\'optimisation des performances des joueurs. Des outils d’IA permettent d’analyser des milliers de parties et de détecter les points faibles des joueurs ou des équipes. Des applications comme SenpAI ou Mobalytics fournissent des conseils personnalisés et proposent des analyses approfondies pour aider les joueurs à améliorer leur stratégie et à progresser plus rapidement. Cette technologie rend les analyses de match plus précises et renforce la préparation des équipes avant les compétitions.\r\n\r\n4. L\'Émergence des Esports dans les Écoles et Universités\r\nLe développement des esports dans les écoles et les universités continue de s\'accélérer en 2024. De plus en plus d\'établissements d\'enseignement proposent des programmes de formation et des équipes esportives pour encourager les jeunes talents. Aux États-Unis, mais aussi en Europe et en Asie, plusieurs universités offrent désormais des bourses pour les joueurs prometteurs, mettant les esports au même niveau que les sports traditionnels. Cette professionnalisation précoce donne aux jeunes joueurs l\'opportunité de se former tout en construisant une carrière dans l’esport.\r\n\r\n5. La Montée en Puissance des Jeux de Stratégie en Temps Réel (RTS)\r\nLes jeux de stratégie en temps réel, ou RTS, font un retour en force. Des titres classiques comme Starcraft II continuent de captiver des audiences, tandis que de nouveaux jeux comme Stormgate, développé par d’anciens créateurs de Warcraft et Starcraft, promettent de revitaliser ce genre. Les RTS exigent des compétences de réflexion rapide et de stratégie, ce qui en fait des compétitions captivantes pour les spectateurs. En 2024, la montée des RTS est soutenue par un engouement renouvelé pour des jeux plus axés sur la stratégie et la réflexion.', 3),
(17, 'Les Bienfaits du Yoga pour les Sportifs : Plus qu\'une Simple Détente', 'Le yoga est devenu un allié de choix pour de nombreux athlètes et sportifs de haut niveau qui recherchent des moyens d’améliorer leurs performances, tout en évitant les blessures et en favorisant une meilleure récupération. En intégrant le yoga à leur routine d\'entraînement, les sportifs bénéficient d\'un complément essentiel qui favorise le renforcement physique, la flexibilité et la santé mentale. Dans cet article, découvrez les principaux bienfaits du yoga et comment il peut améliorer la condition des athlètes, qu\'ils soient amateurs ou professionnels.\r\n\r\n1. Amélioration de la Flexibilité\r\nLa flexibilité est cruciale pour de nombreux sports, et le yoga est particulièrement efficace pour l\'améliorer. Les postures de yoga, comme le chien tête en bas, le cobra et la fente, permettent d\'étirer les muscles et les tendons en profondeur. Cette amélioration de la souplesse réduit le risque de blessures musculaires et tendineuses, car elle permet aux muscles de mieux absorber les chocs et les impacts. Pour les athlètes, une plus grande flexibilité se traduit également par une plus grande amplitude de mouvement, facilitant les performances.\r\n\r\n2. Renforcement Musculaire\r\nLe yoga est également une discipline de renforcement. Contrairement aux exercices de musculation qui isolent souvent des groupes musculaires, le yoga utilise le poids du corps pour renforcer l\'ensemble des muscles, y compris ceux souvent négligés dans d\'autres entraînements. Des postures comme la planche, la chaise, et le guerrier sollicitent les muscles profonds, en particulier ceux du tronc, qui jouent un rôle essentiel pour la stabilité et l\'équilibre. Cette approche complète contribue à un corps plus équilibré, réduisant les déséquilibres musculaires et favorisant une meilleure posture.\r\n\r\n3. Amélioration de la Récupération\r\nLes séances de yoga, en particulier celles axées sur la relaxation et la respiration, aident les athlètes à récupérer plus rapidement après un entraînement intense ou une compétition. La respiration profonde et contrôlée favorise une meilleure oxygénation des muscles, tandis que les étirements doux aident à libérer les tensions accumulées. Le yoga réparateur, une forme de yoga doux et apaisant, est particulièrement recommandé pour détendre les muscles fatigués et stimuler la circulation sanguine, essentielle pour une récupération optimale.\r\n\r\n4. Réduction du Stress et Meilleure Concentration\r\nLe stress mental est un défi pour de nombreux athlètes, surtout en période de compétition. Le yoga, grâce à des techniques de respiration et de méditation, permet de réduire l\'anxiété et de rester centré. La pratique régulière du yoga aide à améliorer la concentration et la clarté mentale, ce qui peut être un atout dans les sports qui exigent un haut niveau de précision et de prise de décision rapide. Apprendre à contrôler la respiration permet aussi de mieux gérer le stress, favorisant un état d\'esprit calme et équilibré, essentiel pour les performances sportives.\r\n\r\n5. Amélioration de l\'Équilibre et de la Coordination\r\nDes postures comme l’arbre, le corbeau ou le guerrier III travaillent directement sur l\'équilibre et la coordination. Pour les athlètes, cet entraînement spécifique est précieux car il améliore la conscience corporelle, ou proprioception. Une meilleure coordination permet de mieux se mouvoir et d\'être plus efficace dans les mouvements, ce qui est particulièrement important pour les sports de précision comme le ski, le tennis ou l\'escrime.', 3),
(18, 'Pourquoi le Rugby est Devenu l\'un des Sports les Plus Populaires en France', 'Le rugby connaît une ascension fulgurante en France, s\'imposant comme l\'un des sports les plus prisés du pays. Cette popularité croissante peut être attribuée à plusieurs facteurs, allant des valeurs fondamentales du sport à des succès marquants sur la scène internationale.\r\n\r\nLes valeurs du rugby sont au cœur de son attrait. Ce sport promeut la camaraderie, le respect et l\'esprit d\'équipe, des éléments qui résonnent profondément avec les valeurs de la société française. Dans un monde où l\'individualisme prédomine, le rugby offre une bulle de solidarité et d\'entraide. Les matchs, qu\'ils soient amateurs ou professionnels, rassemblent les communautés, créant un fort sentiment d\'appartenance.\r\n\r\nL\'impact culturel du rugby est également significatif. Le sport s\'est enraciné dans la culture régionale, notamment dans le Sud-Ouest, où il est devenu un véritable mode de vie. Les clubs de rugby, souvent liés à des villes et villages, sont des institutions qui cultivent la passion et le soutien local. Les supporters, vêtus de leurs couleurs, remplissent les stades lors des grands matchs, renforçant ainsi le lien social.\r\n\r\nLes succès récents de l’équipe nationale ont aussi joué un rôle majeur dans la montée en popularité du rugby. Avec des performances impressionnantes dans des compétitions telles que le Tournoi des Six Nations et la Coupe du Monde, l\'équipe a su captiver l\'attention du public. Les victoires et les moments forts partagés par les joueurs sur le terrain ont suscité une fierté nationale, attirant de nouveaux fans et revitalisant l\'intérêt pour le sport.\r\n\r\nEn conclusion, le rugby est devenu un véritable symbole de la culture sportive française. Ses valeurs, son impact communautaire et ses succès sur le terrain en font un sport incontournable, attirant des millions de passionnés. Avec la Coupe du Monde 2023, la ferveur autour de ce sport ne devrait qu’augmenter, consolidant ainsi sa place dans le cœur des Français.', 4),
(19, 'Les Sports Nautiques : Un Guide pour Débuter', 'Les sports nautiques, tels que le surf, le kayak et la planche à voile, offrent une excellente manière de se connecter avec la nature tout en se maintenant actif. Pour ceux qui souhaitent débuter, voici un guide pratique pour naviguer dans cet univers passionnant.\r\n\r\n1. Choisir le bon sport : Chaque sport nautique présente ses propres défis et plaisirs. Le surf, par exemple, demande une certaine maîtrise des vagues et du timing, tandis que le kayak offre une expérience plus tranquille sur l\'eau. La planche à voile, quant à elle, combine les éléments du surf et de la navigation. Évaluez vos préférences pour déterminer le sport qui vous conviendrait le mieux.\r\n\r\n2. Équipements nécessaires : Quel que soit le sport choisi, investir dans le bon équipement est essentiel. Pour le surf, une planche adaptée à votre niveau et une combinaison sont indispensables. Pour le kayak, un kayak stable et un gilet de sauvetage sont essentiels. La planche à voile nécessite également du matériel spécifique, notamment une planche, une voile et des accessoires de sécurité. N\'hésitez pas à demander conseil dans des magasins spécialisés ou à des instructeurs.\r\n\r\n3. Les meilleurs spots : Pratiquer dans un environnement sûr est crucial pour débuter. Les plages avec des vagues modérées sont idéales pour le surf, tandis que les lacs ou rivières calmes conviennent parfaitement au kayak. Pour la planche à voile, recherchez des zones avec des vents constants. Renseignez-vous sur les écoles locales qui proposent des cours pour apprendre les bases en toute sécurité.\r\n\r\n4. Conseils de sécurité : Avant de vous lancer, il est vital de connaître les règles de sécurité. Toujours porter un gilet de sauvetage, se renseigner sur les conditions météorologiques et éviter de pratiquer seul sont des précautions à prendre. De plus, respecter l\'environnement marin et les autres pratiquants est essentiel pour garantir une expérience agréable pour tous.\r\n\r\nEn résumé, les sports nautiques sont une excellente manière de profiter des bienfaits de l\'eau tout en s\'amusant. Avec les bons conseils, équipements et spots, vous serez sur la bonne voie pour vivre des moments inoubliables sur l\'eau. Que vous choisissiez le surf, le kayak ou la planche à voile, lancez-vous et découvrez la liberté que ces sports peuvent offrir !', 5);

-- --------------------------------------------------------

--
-- Structure de la table `article_categories`
--

CREATE TABLE `article_categories` (
  `id_article` int(4) NOT NULL,
  `id_category` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `article_categories`
--

INSERT INTO `article_categories` (`id_article`, `id_category`) VALUES
(14, 51),
(14, 55),
(14, 56),
(15, 51),
(15, 56),
(16, 57),
(17, 43),
(17, 51),
(17, 56),
(18, 50),
(19, 49);

-- --------------------------------------------------------

--
-- Structure de la table `category`
--

CREATE TABLE `category` (
  `id_category` int(4) NOT NULL,
  `name_category` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `category`
--

INSERT INTO `category` (`id_category`, `name_category`) VALUES
(43, 'Athlétisme'),
(41, 'Basketball'),
(45, 'Cyclisme'),
(57, 'Esports et jeux vidéo'),
(40, 'Football'),
(53, 'Golf'),
(47, 'Gymnastique'),
(58, 'Handball'),
(51, 'Musculation et fitness'),
(44, 'Natation'),
(50, 'Rugby'),
(55, 'Running et marathon'),
(48, 'Sports d\'hiver'),
(46, 'Sports de combat'),
(52, 'Sports extrêmes'),
(54, 'Sports mécaniques'),
(49, 'Sports nautiques'),
(59, 'Volleyball'),
(56, 'Yoga et bien-être');

-- --------------------------------------------------------

--
-- Structure de la table `comment`
--

CREATE TABLE `comment` (
  `id_comment` int(4) NOT NULL,
  `id_user` int(4) NOT NULL,
  `id_article` int(4) NOT NULL,
  `description` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id_user` int(4) NOT NULL,
  `pseudo` varchar(30) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(65) NOT NULL,
  `userType` enum('simpleUser','admin') NOT NULL DEFAULT 'simpleUser'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id_user`, `pseudo`, `email`, `password`, `userType`) VALUES
(1, 'admin1', 'admin@localhost.fr', '2b0adf4e78b8c1d0e144590cdb4570802eb803f736a5de328293461970acce47', 'admin'),
(2, 'user1', 'user1@localhost.fr', 'f3029a66c61b61b41b428963a2fc134154a5383096c776f3b4064733c5463d90', 'simpleUser'),
(3, 'user2', 'user2@localhost.fr', 'f3029a66c61b61b41b428963a2fc134154a5383096c776f3b4064733c5463d90', 'simpleUser'),
(4, 'user3', 'user3@localhost.fr', 'f3029a66c61b61b41b428963a2fc134154a5383096c776f3b4064733c5463d90', 'simpleUser'),
(5, 'user4', 'user4@localhost.fr', 'f3029a66c61b61b41b428963a2fc134154a5383096c776f3b4064733c5463d90', 'simpleUser'),
(6, 'user5', 'user5@localhost.fr', 'f3029a66c61b61b41b428963a2fc134154a5383096c776f3b4064733c5463d90', 'simpleUser');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `article`
--
ALTER TABLE `article`
  ADD PRIMARY KEY (`id_article`),
  ADD KEY `fk_article_author` (`id_author`);

--
-- Index pour la table `article_categories`
--
ALTER TABLE `article_categories`
  ADD PRIMARY KEY (`id_article`,`id_category`),
  ADD KEY `fk_article_categories_category` (`id_category`);

--
-- Index pour la table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id_category`),
  ADD UNIQUE KEY `name_category` (`name_category`);

--
-- Index pour la table `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`id_comment`),
  ADD KEY `fk_comment_user` (`id_user`),
  ADD KEY `fk_comment_article` (`id_article`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `pseudo` (`pseudo`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `article`
--
ALTER TABLE `article`
  MODIFY `id_article` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT pour la table `category`
--
ALTER TABLE `category`
  MODIFY `id_category` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT pour la table `comment`
--
ALTER TABLE `comment`
  MODIFY `id_comment` int(4) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `article`
--
ALTER TABLE `article`
  ADD CONSTRAINT `fk_article_author` FOREIGN KEY (`id_author`) REFERENCES `user` (`id_user`) ON DELETE CASCADE;

--
-- Contraintes pour la table `article_categories`
--
ALTER TABLE `article_categories`
  ADD CONSTRAINT `fk_article_categories_article` FOREIGN KEY (`id_article`) REFERENCES `article` (`id_article`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_article_categories_category` FOREIGN KEY (`id_category`) REFERENCES `category` (`id_category`) ON DELETE CASCADE;

--
-- Contraintes pour la table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `fk_comment_article` FOREIGN KEY (`id_article`) REFERENCES `article` (`id_article`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_comment_user` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
