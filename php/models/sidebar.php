<div class="sidebar">
	<div id="menu" class="menu">
		<a href="journal.php" class="item <?php if($page=="journal"){echo "is-active";}?>">
				<span class="item-icon icon-journal"></span>
				<span class="item-text">Journal</span>
		</a>
		<div class="item item-as-dropdown">
				<span class="item-icon icon-user"></span>
				<span class="item-text">Utilisateur</span>
				<span class="item-icon icon-dropdown"></span>
		</div>
				<div class="dropdown">
					<a href="list-user.php" class="item-dropdown">
						<span class="item-icon icon-list"></span>
						<span class="item-text">Liste des utilisateurs</span>
					</a>
                    <a href="change-code.php" class="item-dropdown">
                        <span class="item-icon icon-change"></span>
                        <span class="item-text">Modifier un code</span>
                    </a>
					<a href="add-user.php" class="item-dropdown">
						<span class="item-icon icon-add"></span>
						<span class="item-text">Ajouter un utilisateur</span>
					</a>
				</div>
		<a href="unlock.php" class="item <?php if($page=="unlock"){echo "is-active";}?>">
				<span class="item-icon icon-key"></span>
				<span class="item-text">Déverrouiller la gâche</span>
		</a>
	</div>
</div>