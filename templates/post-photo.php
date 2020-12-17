<div class="post__main">
    <div class="post-details__image-wrapper post-photo__image-wrapper">
        <?php foreach ($posts as $post) :

            if ($post['id'] == $id) {
                echo '<img src="img/' . $post['picture'] . '" alt="Фото от пользователя" width="760" height="507">';
            }

        endforeach; ?>
    </div>
</div>