USE TP2_CERVO_MARIABELEN;

INSERT INTO interests (interest)
VALUES ('amigos'), ('una relación');

INSERT INTO categories (category)
VALUES ('skatepark'), ('spot');

INSERT INTO users (name, lastname, email, password, photo, startdate, location, birthday, fkinterest)
VALUES
	('María Belén', 'Cervo', 'belucervo@gmail.com', '$2y$10$9mJoUch/wtHz/VQSVd8VvO1yLGJvL2u6uax1IdaF7PSbYtHu7cXHq', 'belucervo.jpg', NOW(), 'CABA, Argentina', '1993-08-25', 1),
	('Santiago', 'Gallino', 'santiagogallino@gmail.com', '$2y$10$nY19mez7HvyPZbL1vz9x4ON4i7eGapWt7NPz6I.swRKMdSqeWWWcm', 'santiagogallino.jpg', NOW(), 'CABA, Argentina', '1985-07-12', 1),
	('Nicolás', 'Di Giorgi', 'nicodigiori@gmail.com', '$2y$10$zw50ciRwIZJAC/0bKLv0O.zxAGlGg6t3toxN2Wll0Wf5aArpizSKy', 'nicodigiori.jpg', NOW(), 'Mendoza, Argentina', '1986-09-02', 2),
	('Guillermo', 'Otero', 'guilleotero@gmail.com', '$2y$10$PdqW6YBxhLN8hV1st.Ty8u70IG8DatcRfwyCQPHasKYVm0sHrLOzy', 'guilleotero.jpg', NOW(), 'La Pampa, Argentina', '1998-08-22', 1);

INSERT INTO posts (photo, title, location, description, startdate, fkuser, fkcategory)
VALUES
	('pquecentenario.jpg', 'PQUE CENTENARIO', 'Av. Patricias Argentinas', 'Lo mejor de este parque es el fácil acceso y las rampas. ¡Tienen que ir!', NOW(), 1, 1),
	('facultades.jpg', 'FACULTADES', 'J. E. Uriburu 826, CABA', 'Está muy bueno. Lástima que está en un lugar muy céntrico donde pasa mucha gente todo el tiempo y el ruido es muy molesto.Escaleras empinadas.', NOW(), 2, 1),
	('barracas.jpg', 'BARRACAS', 'Herrera 350, CABA', 'Un poco alejado. Eso está bueno porque podés divertirte tranquilo sin que nadie te moleste, y los vecinos del lugar ya están acostumbrados.', NOW(), 3, 1),
	('villaurquiza.jpg', 'VILLA URQUIZA', 'Av. Triunvirato 4763, CABA', 'Una joya sin descubrir. Es genial para practicar los saltos y piruetas más difíciles. Lo mejor es lo tranquilo del lugar.', NOW(), 4, 2),
	('bajobelgrano.jpg', 'BAJO BELGRANO', 'Echeverría 202, CABA', 'Increíble spot, pero no vayan cerca de las 5 de la tarde porque se llena de niños pequeños.', NOW(), 1, 2);

INSERT INTO user_comments_post (fkuser, fkpost, startdate, comment)
VALUES
	(1, 1, NOW(), '¡El mejor skatepark!'),
	(2, 1, NOW(), '¿Quién se suma a la juntada del vierneees?'),
	(4, 1, NOW(), '¡Yo! ¿A qué hora?'),
	(2, 2, NOW(), 'Este es el que más me gusta'),
	(2, 3, NOW(), 'Ya me aburrí de este parque'),
	(1, 3, NOW(), '¡Estás loco!'),
	(4, 4, NOW(), 'Le falta un poco de onda a este'),
	(1, 4, NOW(), '¡A mi me encanta!'),
	(4, 5, NOW(), '¿Y para ir a este quién se suma?'),
	(3, 5, NOW(), 'Otro día. Hoy no puedo.');