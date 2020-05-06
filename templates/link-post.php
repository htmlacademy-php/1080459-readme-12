<div class="post__main">
    <div class="post-link__wrapper">
        <a class="post-link__external" href="http://<?=$post['content'];?>" title="Перейти по ссылке">
            <div class="post-link__info-wrapper">
                <div class="post-link__icon-wrapper">
                    <img src="https://www.google.com/s2/favicons?domain=<?=$post['url'];?>" alt="Иконка">
                </div>
                <div class="post-link__info">
                    <h3><?=$post['title'];?></h3>
                </div>
            </div>
            <span><?=htmlspecialchars($post['content'])?></span>
        </a>
    </div>
</div>
