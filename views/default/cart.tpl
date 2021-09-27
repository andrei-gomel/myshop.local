{* шаблон корзины *}

<h1>Корзина</h1>

{if ! $rsProducts}
В корзине пусто
{else}
<form action="/cart/order/" method="POST">
<h2>Данные заказа</h2>
<table>
  <tr>
    <td>№</td>
    <td>Наименование</td>
    <td>Количество</td>
    <td>Цена за единицу</td>
    <td>Стоимость</td>
    <td>Действие</td>
  </tr>
  {foreach $rsProducts as $item name=products}
  <tr>
    <td>{$smarty.foreach.products.iteration}</td>
    <td>
      <a href="/product/{$item['id']}/">{$item['name']}</a>
    </td>
    <td>
      <input name="itemCnt_{$item['id']}" size="4" id="itemCnt_{$item['id']}" type="number" min="1" max="10" value="1" onchange="conversionPrice({$item['id']});">
    </td>
    <td>
      <span id="itemPrice_{$item['id']}" value="{$item['price']}">
        {$item['price']}
      </span>
    </td>
    <td>
      <span id="itemRealPrice_{$item['id']}" value="{$item['price']}">
        {$item['price']}
      </span>
    </td>
    <td>
      <a id="removeCart_{$item['id']}"  href="#" onClick="removeFromCart({$item['id']}); return false;" alt="Удалить из корзины">Удалить</a>
      <a id="addCart_{$item['id']}" class="hideme" href="#" onClick="addToCart({$item['id']}); return false;" alt="Восстановить элемент">Восстановить</a>
    </td>
  </tr>
  {/foreach}
</table>
<input type="submit" value="Оформить заказ">
</form>
{/if}
