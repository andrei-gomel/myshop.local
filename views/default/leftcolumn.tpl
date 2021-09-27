{* Левый столбец *}

<div id="leftColumn">

	<div id="leftMenu">
		<div class="menuCaption">Меню:</div>
			{foreach $rsCategories as $item}
				<a href="/?controller=category&id={$item['id']}" style="color: #228B22">{$item['name']}</a><br>

				{if isset($item['children'])}
					{foreach $item['children'] as $itemChild}
						--<a href="/?controller=category&id={$itemChild['id']}" style="color: #228B22">{$itemChild['name']}</a><br>
					{/foreach}
				{/if}
			{/foreach}
		</div>

		{if isset($arUser)}
			<div id="userBox">
				<a href="/user/" id="userLink">{$arUser['displayName']}</a><br>
				<a href="/user/logout/">Выход</a>
			</div>
		{else}

			<div id="userBox" class="hideme">
				<a href="#" id="userLink"></a><br>
				<a href="/user/logout/" onclick="logout();">Выход</a>
			</div>

			{if ! isset($hideLoginBox)}

				<div id="loginBox">
					<div class="menuCaption">Авторизация</div>
					<input type="text" id="loginEmail" name="loginEmail" value=""><br>
					<input type="password" id="loginPwd" name="loginPwd" value=""><br>
					<input type="button" onclick="login();" value="Войти">
				</div>

				<div id="registerBox">
					<div class="menuCaption showHidden" onclick="showRegisterBox();">Регистрация</div>
					<div id="registerBoxHidden">
						email:<br>
						<div>
						<input type="text" id="email" name="email" value="" onchange="checkEmailUser();">
						<span id="email-message"></span>
						</div>

						пароль:<br>
						<input type="password" id="pwd1" name="pwd1" value=""><br>
						повторите пароль:<br>
						<input type="password" id="pwd2" name="pwd2" value=""><br>

						<input type="button" onclick="registerNewUser();" value="Зарегистрироваться">
					</div>
				</div>
				{/if}
		{/if}

		<div class="menuCaption">Корзина</div>
		<a href="/cart/" title="Перейти в корзину">В корзине</a>
		<span id="cartCntItems">
			{if $cartCntItems > 0}{$cartCntItems}{else}пусто{/if}
		</span>
	</div>
