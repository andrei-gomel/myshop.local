{* шаблон главной страницы *}

{foreach $rsProducts as $item name=products}

	<div  class="border_style_dotted" style="width:270px; float: left; margin: 5px 10px; padding: 10px 10px 10px 10px;">
		<a href="/product/{$item['id']}/">
			<img src="/images/products/{$item['image']}" width="250">
		</a><br>
		<a href="/product/{$item['id']}/" style="color: #228B22">{$item['name']}</a>
	</div>

{/foreach}
