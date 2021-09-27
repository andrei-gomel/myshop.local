<h2>Товар</h2>

  <table border="1" bordercolor="#5F9EA0" cellspacing="0" cellpadding="0" style="width:800px;">
    <h4>Добавить продукт:</h4>
    <tr>
      <th>Название</th>
      <th>Цена</th>
      <th>Категория</th>
      <th>Описание</th>
      <th>Сохранить</th>
    </tr>
    <tr>
      <td>
        <input type="edit" id="newItemName" value="">
      </td>
      <td>
        <input type="edit" id="newItemPrice" value="" maxlength="10" size="10">
      </td>
      <td>
        <select id="newItemCatId">
          <option value="0">Главная категория
            {foreach $rsCategories as $itemCat}
              <option value="{$itemCat['id']}">{$itemCat['name']}
            {/foreach}
        </select>
      </td>
      <td style="width:350px;">
        <textarea id="newItemDesc" cols="40" rows="5"></textarea>
      </td>
      <td>
        <input type="button" value="Сохранить" onclick="addProduct();">
      </td>
    </tr>
  </table>

  <table border="1" bordercolor="#5F9EA0" cellspacing="0" cellpadding="0">
    <h4>Редактировать:</h4>
    <tr>
      <th>№</th>
      <th>ID</th>
      <th>Название</th>
      <th>Цена</th>
      <th>Категория</th>
      <th style="width:300px;">Описание</th>
      <th>Удалить</th>
      <th>Изображение</th>
      <th>Сохранить</th>
    </tr>
    {foreach $rsProducts as $item name=products}
      <tr>
        <td>{$smarty.foreach.products.iteration}</td>
        <td>{$item['id']}</td>
        <td><input type="edit" id="itemName_{$item['id']}"
          value="{$item['name']}">
        </td>
        <td><input type="edit" id="itemPrice_{$item['id']}"
          value="{$item['price']}" maxlength="10" size="10">
        </td>
        <td>
          <select id="itemCatId_{$item['id']}">
            <option value="0">Главная категория
              {foreach $rsCategories as $itemCat}
                <option value="{$itemCat['id']}"
                {if $itemCat['id'] == $item['category_id']} selected{/if}>{$itemCat['name']}
              {/foreach}
          </select>
        </td>
        <td style="width:300px;">
          <textarea id="itemDesc_{$item['id']}" cols="40" rows="8">{$item['description']}
          </textarea>
        </td>
        <td>
          <input type="checkbox" id="itemStatus_{$item['id']}"
            {if $item['status'] == 0}checked="checked"{/if}>
          </td>
        <td>
          {if $item['image']}
          <img src="/images/products/{$item['image']}" width="100">
          {/if}
          <form method="post" action="/admin/upload/" enctype="multipart/form-data">
            <input type="file" name="filename"><br>
            <input type="hidden" name="itemId" value="{$item['id']}">
            <input type="submit" value="Загрузить"><br>
          </form>
        </td>
        <td>
          <input type="button" value="Сохранить" onclick="updateProduct('{$item['id']}');">
        </td>
      </tr>
    {/foreach}
  </table>
