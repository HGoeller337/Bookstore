DROP DATABASE IF EXISTS cs4300project;
CREATE DATABASE cs4300project;
USE cs4300project;

CREATE TABLE writers (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
    first_name VARCHAR(30) NOT NULL,
    last_name VARCHAR(30) NOT NULL,
	birth_year INT NOT NULL,
    bio VARCHAR(1000)
);

CREATE TABLE genres (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
    name VARCHAR(20) NOT NULL,
	is_fiction BOOLEAN NOT NULL
);

CREATE TABLE books (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
    title VARCHAR(50) NOT NULL,
    author INT UNSIGNED NOT NULL,
    genre INT UNSIGNED NOT NULL,
	publish_year INT NOT NULL,
    word_count INT UNSIGNED NOT NULL,
    price DOUBLE UNSIGNED NOT NULL,
    stock INT UNSIGNED NOT NULL,
    CONSTRAINT FK_bookgenre FOREIGN KEY (genre) REFERENCES genres(id),
    CONSTRAINT FK_bookauthor FOREIGN KEY (author) REFERENCES writers(id)
);

CREATE TABLE users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
    email VARCHAR(50) UNIQUE NOT NULL,
    pass VARCHAR(30) NOT NULL,
    is_admin BOOLEAN NOT NULL,
    session_id VARCHAR(32) UNIQUE,
    first_name VARCHAR(30),
    last_name VARCHAR(30),
    bio VARCHAR(1000),
    fav_book INT UNSIGNED,
    fav_author INT UNSIGNED,
    cart VARCHAR(2048) NOT NULL,
    CONSTRAINT FK_userbook FOREIGN KEY (fav_book) REFERENCES books(id),
    CONSTRAINT FK_userauthor FOREIGN KEY (fav_author) REFERENCES writers(id)
);

CREATE TABLE reviews (
    critic INT UNSIGNED NOT NULL,
    book INT UNSIGNED NOT NULL,
    score TINYINT UNSIGNED NOT NULL,
    critique VARCHAR(1000),
    CONSTRAINT FK_reviewcritic FOREIGN KEY (critic) REFERENCES users(id),
    CONSTRAINT FK_reviewbook FOREIGN KEY (book) REFERENCES books (id)
);

INSERT INTO genres(name, is_fiction) VALUES
('Science Fiction', TRUE),
('Novel', TRUE),
('Fantasy', TRUE),
('Comedy', TRUE),
('Comedy', FALSE),
('Romance', TRUE),
('Mystery', TRUE),
('Biography', FALSE);

INSERT INTO writers (first_name, last_name, birth_year) VALUES
('Ray', 'Bradbury', 1920),
('Charlotte', 'Brontë', 1816),
('Richard', 'Adams', 1920),
('Mark', 'Twain', 1835),
('Douglas', 'Adams', 1952),
('Orson', 'Card', 1951),
('John', 'Tolkien', 1892),
('Madeline', "L'Engle", 1918),
('Stephen', 'King', 1947),
('Diana', 'Jones', 1934),
('Firoozeh', 'Dumas', 1965),
('Carrie', 'Fisher', 1956),
('Elif', 'Batuman', 1977),
('Joe', 'Dunthorne', 1982),
('Jane', 'Austen', 1775),
('Margaret', 'Mitchell', 1900),
('Judith', 'McNaught', 1944),
('Lisa', 'Wingate', 1965),
('Stieg', 'Larsonn', 1954),
('Agatha', 'Christie', 1890),
('Daphne', 'du Maurier', 1907),
('Anne', 'Frank', 1929),
('Rebecca', 'Skloot', 1972),
('Frederick', 'Douglass', 1817);

INSERT INTO writers (first_name, last_name, birth_year, bio) VALUES
('Barbara', 'Pym', 1913,
	CONCAT(
		'Born on June 2nd, 1913, Barbara Mary Crampton Pym was an english ',
		'novelist from the 30s up to the end of her life in 1980. Although ',
		'she did not achieve great success for most of her career, she ',
		'experienced a great resurgence in 1977 as high-profile literary ',
		'figures lauded her as one of the most underrated writers of the ',
		'century. Her works are known to have a unique style and have strong ',
		'character writing, and all of her books exist in one consistent ',
		'universe. Her writings usually portray suburban life, with more ',
		'emphasis on character relationships and humor than overall plot. ',
		'Interested? If so, we carry several of her novels!'
	)
);

INSERT INTO books (title, author, genre, publish_year, word_count, price, stock) VALUES
('Fahrenheit 451', 1, 1, 1953, 46118, 12.99, 9),
('Jane Eyre', 2, 2, 1847, 183858, 7.99, 9),
('Watership Down', 3, 2, 1972, 156154, 13.99, 5),
('The Adventures of Huckleberry Finn', 4, 2, 1884, 109571, 1.99, 6),
("The Hitchhiker's Guide to the Galaxy", 5, 1, 1979, 46333, 7.99, 1),
("Ender's Game", 6, 1, 1985, 100609, 7.99, 6),
('The Lord of the Rings', 7, 3, 1968, 472103, 16.99, 0),
('A Wrinkle in Time', 8, 3, 1962, 49965, 6.99, 3),
('The Dark Tower: The Gunslinger', 9, 3, 1982, 55376, 10.99, 9),
("Howl's Moving Castle", 10, 3, 1986, 75480, 6.99, 7),
('Funny in Farsi', 11, 5, 2003, 62400, 12.99, 9),
('Wishful Drinking', 12, 5, 2008, 44000, 12.99, 0),
('The Idiot', 13, 4, 2017, 68729, 11.99, 10),
('The Adulterants', 14, 4, 2018, 40503, 14.99, 10),
('Pride and Prejudice', 15, 6, 1813, 122189, 5.99, 1),
('Gone With the Wind', 16, 6, 1936, 418053, 9.99, 7),
('Whitney, My Love', 17, 6, 1985, 188300, 7.99, 6),
('Before We Were Yours', 18, 6, 2017, 102640, 12.99, 4),
('The Girl with the Dragon Tattoo', 19, 7, 2008, 165392, 9.99, 9),
('And Then There Were None', 20, 7, 2004, 52656, 7.99, 0),
('Rebecca', 21, 7, 1938, 135000, 10.99, 2),
('The Diary of a Young Girl', 22, 8, 1947, 82762, 6.99, 8),
('The Immortal Life of Henrietta Lacks', 23, 8, 2010, 101230, 10.99, 6),
('Narrative of the Life of Frederick Douglass', 24, 8, 1845, 65520, 3.99, 3),
('Excellent Women', 25, 2, 1952, 75525, 12.99, 0),
('Jane and Prudence', 25, 2, 1953, 80620, 10.99, 4),
('Quartet in Autumn', 25, 2, 1977, 56000, 11.99, 1),
('A Few Green Leaves', 25, 2, 1980, 71398, 7.99, 1);

INSERT INTO users (email, pass, is_admin, first_name, last_name, bio, fav_book, fav_author, cart) VALUES
('admin@localhost', 'password', TRUE, 'Admin', '', NULL, NULL, NULL, '{}'),
('avidreader@gmail.com', 'seafood', FALSE, 'Barbara', 'Jackson', NULL, 19, 19, '{}'),
('dolores32@hotmail.com', 'Shampoo4', FALSE, 'Dolores', 'Taylor', NULL, 22, 11, '{}'),
('bookbookbookbook@gmail.com', 'icantread', FALSE, 'Libra', 'Thompson', NULL, 12, 7, '{}'),
('hatereading@outlook.com', 'bookssuck', FALSE, 'Bookhater', 'Johnson', NULL, 13, 12, '{}'),
('carriefan7@yahoo.com', 'starredwar', FALSE, 'Carry', 'Fisherman', NULL, 12, 12, '{}'),
('siteowner@uga.edu', 'icontrolall', TRUE, 'Bookstore', 'Owner', NULL, 27, 25, '{}'),
('mybffkevin@gmail.com', 'kevinfriend456', FALSE, 'Kevin', 'Kevin', NULL, 16, 8, '{}'),
('kevinistheworst@yahoo.com', 'kevinhater123', FALSE, 'Kevin', 'Sucks', NULL, 8, 16, '{}'),
('prariegirl99@hotmail.com', 'sunset3', FALSE, 'Delilah', 'Garland', NULL, 17, 18, '{}'),
('lavenderfields@yahoo.com', 'quartz77', FALSE, 'Jordan', 'Henderson', NULL, 5, 20, '{}');

INSERT INTO reviews (critic, book, score, critique) VALUES 
(8, 1, 5, 'this happened to my friend kevin lol'),
(9, 1, 1, 'this happened to a guy i hate named kevin >:('),
(2, 1, 5, 
	CONCAT (
	'Easily one of the most influential and important books written to date. ',
	'I highly recommend giving it a read, because many of its core themes have ',
	'only become more relevant as time goes on. If you are looking for a book ',
	'that will make you think about the state of the world today, definitely ',
	'give this one a try.'
	)
),
(4, 20, 4,
	CONCAT (
	'One of the best books I have ever seen. The pages are incredibly crisp, ',
	'and I love the cover. I had to take off a point, though, because it ',
	'did not have enough pictures. '
	)
),
(11, 20, 5, 'I love mystery novels, and this one is excellent!'),
(10, 20, 3, 'it was okay, but i dont get the ending'),
(7, 20, 4, 'I really like this one. It may not be perfect, but it is worth your time.'),
(5, 15, 1, 'i hate this book so much that i bought it just to give it 1 star'),
(10, 15, 5, 'i love this book sooooo much. its just so romantic!'),
(10, 15, 2, 'How do I get back on Facebook?? I have to call my grandson'),
(2, 15, 5,
	CONCAT (
	'Another timeless classic that will forever have a place in the literary ',
	'world. This book is not for everyone, but I highly recommend it for any ',
	'fans of the genre.'
	)
);
