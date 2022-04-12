
/**
 * 
* Pour pouvoir utiliser le systèeme de chiffrement de mot de passe de silex 2 j'ai dû modifier la requete content.sql pour inclure un contenu 
* de la table user avec des mots de passes chiffrés fonctionnant avec silex 2
*
 *  voir https://github.com/bpesquet/OC-MicroCMS/blob/iteration-10/db/content.sql pour retrouver le contenu de la requete concernant la table user
 */


/*********************contenu de la requete issu de la partie du cours sur microcms**************/

/* raw password is 'john' */
insert into t_user values
(1, 'JohnDoe', '$2y$13$F9v8pl5u5WMrCorP9MLyJeyIsOLj.0/xqKd/hqa5440kyeB7FQ8te', 'YcM=A$nsYzkyeDVjEUa7W9K', 'ROLE_USER');
/* raw password is 'jane' */
insert into t_user values
(2, 'JaneDoe', '$2y$13$qOvvtnceX.TjmiFn4c4vFe.hYlIVXHSPHfInEG21D99QZ6/LM70xa', 'dhMTBkzwDKxnD;4KNs,4ENy', 'ROLE_USER');
/* raw password is '@dm1n' */
insert into t_user values
(3, 'admin', '$2y$13$A8MQM2ZNOi99EW.ML7srhOJsCaybSbexAj/0yXrJs4gQ/2BqMMW2K', 'EDDsl&fBCJB|a5XUtAlnQN8', 'ROLE_ADMIN');



/************************ contenu initial de la requete****************/
insert into t_link values
(1, "Les « dev » ces nouvelles stars que l'on s'arrache", 'http://www.lesechos.fr/journal20141121/lec1_enquete/0203908469986-les-dev-ces-nouvelles-stars-que-lon-sarrache-1066627.php', 1);
insert into t_link values
(2, 'The state of JavaScript in 2015', 'http://www.breck-mckye.com/blog/2014/12/the-state-of-javascript-in-2015/', 1);
insert into t_link values
(3, "Guide d'autodéfense numérique", 'http://guide.boum.org/', 2);
insert into t_link values
(4, 'Controverse du GamerGate', 'http://fr.wikipedia.org/wiki/Controverse_du_Gamergate', 2);
