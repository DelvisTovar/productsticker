<ul class="product-sticker-ul">
	{foreach from=$stickers item=sticker}
		<li class="product-sticker-li">
	    	<img class="product-sticker-img {$sticker.sticker_class}" src="{$urls.base_url}modules/productsticker/images/{$sticker.sticker}">
	    </li>
	{/foreach}
</ul>