<?php

/**
 * Поиск записей
 *
 * @param  mixed $connection
 * @param  mixed $keywords
 * @return void
 */
function search_posts($connection, $keywords)
{
    $search_query = "SELECT posts.*, users.username, users.avatar, content_types.type_class,
    COALESCE(like_count, 0) AS likes,
    COALESCE(comment_count, 0) AS comments
    FROM posts
    INNER JOIN users ON posts.author_id=users.id
    INNER JOIN content_types ON posts.post_type=content_types.id
    LEFT JOIN (SELECT post_id, COUNT(*) AS like_count
    FROM likes
    GROUP BY post_id) like_counts ON like_counts.post_id = posts.id
    LEFT JOIN (SELECT post_id, COUNT(*) AS comment_count
    FROM comments
    GROUP BY post_id) comment_counts ON comment_counts.post_id = posts.id ";

    $search_by_tag_query = $search_query . "INNER JOIN post_tags
    INNER JOIN hashtags ON hashtags.id = post_tags.hashtag_id
    WHERE posts.id = post_tags.post_id AND hashtags.tag_name = ?";

    $search_by_keywords_query = $search_query . "WHERE MATCH(title,content) AGAINST(?)";

    $search_results_mysqli =
    (substr($keywords, 0, 1) == '#')
    ? secure_query($connection, $search_by_tag_query, substr($keywords,1))
    : secure_query($connection, $search_by_keywords_query, $keywords);
    $search_results = mysqli_fetch_all($search_results_mysqli, MYSQLI_ASSOC);
    return $search_results;
}
