/* список типов контента для поста */
INSERT into content_types SET type_name = 'Цитата', type_class = 'quote';
INSERT into content_types SET type_name = 'Текст', type_class = 'text';
INSERT into content_types SET type_name = 'Фото', type_class = 'photo';
INSERT into content_types SET type_name = 'Ссылка', type_class = 'link';
INSERT into content_types SET type_name = 'Видео', type_class = 'video';
/* придумайте пару пользователей */
INSERT into users SET username = 'Лариса', email = 'larisa@mail.ru', avatar = 'userpic-larisa-small.jpg', dt_add = '2001-10-04 08:06:57';
INSERT into users SET username = 'Владик', email = 'vladik@mail.ru', avatar = 'userpic.jpg', dt_add = '2002-10-04 08:06:57';
INSERT into users SET username = 'Виктор', email = 'viktor@mail.ru', avatar = 'userpic-mark.jpg', dt_add = '2003-10-04 08:06:57';
/* существующий список постов */
INSERT into posts SET title = 'Цитата', post_type = 1, content = 'Мы в жизни любим только раз, а после ищем лишь похожих', author_id = 1, view_count = 30, quote_author = 'Неизвестный автор', dt_add = '2010-03-24 10:05:13';
INSERT into posts SET title = 'Моя мечта', post_type = 3, content = 'coast-medium.jpg', author_id = 1, view_count = 20, img_url = 'coast.jpg', dt_add = '2015-07-04 17:02:11';
INSERT into posts SET title = 'Игра престолов', post_type = 2, content = 'Не могу дождаться начала финального сезона своего любимого сериала!', author_id = 2, view_count = 10, dt_add = '2020-09-01 00:00:00';
INSERT into posts SET title = 'Наконец, обработал фотки!', post_type = 3, content = 'rock-medium.jpg', author_id = 3, view_count = 999, img_url = 'rock.jpg', dt_add = '2019-03-24 23:05:17';
INSERT into posts SET title = 'Лучшие курсы', post_type = 4, url = 'http://www.htmlacademy.ru', author_id = 2, view_count = 500, dt_add = '2020-07-24 05:08:13';
/* придумайте пару комментариев к разным постам */
INSERT into comments SET user_id = 2, post_id = 4, content = 'Отличные фото!';
INSERT into comments SET user_id = 1, post_id = 5, content = 'Сейчас там учусь';
/* подписчики */
INSERT into subscribe SET follower_id = 2, author_id = 3;
INSERT into subscribe SET follower_id = 1, author_id = 3;
INSERT into subscribe SET follower_id = 3, author_id = 1;
/* получить список постов с сортировкой по популярности и вместе с именами авторов и типом контента */
SELECT users.username, posts.content, posts.view_count, content_types.type_name FROM posts INNER JOIN users ON posts.author_id=users.id INNER JOIN content_types ON posts.post_type=content_types.id ORDER  BY view_count;
/* получить список постов для конкретного пользователя */
SELECT * FROM posts WHERE author_id=1;
/* получить список комментариев для одного поста, в комментариях должен быть логин пользователя */
SELECT comments.content, users.username FROM comments INNER JOIN users ON comments.user_id=users.id WHERE comments.post_id=4;
/* добавить лайк к посту */
INSERT INTO likes SET user_id=1, post_id=3;
/*Сообщения */
INSERT INTO messages SET dt_add = '2020-12-06 10:28:35', content = 'Озеро Байкал – огромное древнее озеро в горах Сибири к северу от монгольской границы. Байкал считается самым глубоким озером в мире. Он окружен сетью пешеходных маршрутов, называемых Большой байкальской тропой. Деревня Листвянка, расположенная на западном берегу озера, – популярная отправная точка для летних экскурсий. Зимой здесь можно кататься на коньках и собачьих упряжках.', sender_id = 1, receiver_id = 5
INSERT INTO messages SET dt_add = '2020-12-06 10:30:12', content = 'Здорова, чувак!', sender_id = 3, receiver_id = 5

