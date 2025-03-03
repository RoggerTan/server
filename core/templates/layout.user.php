<?php
/**
 * @var \OC_Defaults $theme
 * @var array $_
 */

$getUserAvatar = static function (int $size) use ($_): string {
	return \OC::$server->getURLGenerator()->linkToRoute('core.avatar.getAvatar', [
		'userId' => $_['user_uid'],
		'size' => $size,
		'v' => $_['userAvatarVersion']
	]);
}

?><!DOCTYPE html>
<html class="ng-csp" data-placeholder-focus="false" lang="<?php p($_['language']); ?>" data-locale="<?php p($_['locale']); ?>" >
	<head data-user="<?php p($_['user_uid']); ?>" data-user-displayname="<?php p($_['user_displayname']); ?>" data-requesttoken="<?php p($_['requesttoken']); ?>">
		<meta charset="utf-8">
		<title>
			<?php
				p(!empty($_['application'])?$_['application'].' - ':'');
				p($theme->getTitle());
			?>
		</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
		<?php if ($theme->getiTunesAppId() !== '') { ?>
		<meta name="apple-itunes-app" content="app-id=<?php p($theme->getiTunesAppId()); ?>">
		<?php } ?>
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<meta name="apple-mobile-web-app-title" content="<?php p((!empty($_['application']) && $_['appid'] != 'files')? $_['application']:$theme->getTitle()); ?>">
		<meta name="mobile-web-app-capable" content="yes">
		<meta name="theme-color" content="<?php p($theme->getColorPrimary()); ?>">
		<link rel="icon" href="<?php print_unescaped(image_path($_['appid'], 'favicon.ico')); /* IE11+ supports png */ ?>">
		<link rel="apple-touch-icon" href="<?php print_unescaped(image_path($_['appid'], 'favicon-touch.png')); ?>">
		<link rel="apple-touch-icon-precomposed" href="<?php print_unescaped(image_path($_['appid'], 'favicon-touch.png')); ?>">
		<link rel="mask-icon" sizes="any" href="<?php print_unescaped(image_path($_['appid'], 'favicon-mask.svg')); ?>" color="<?php p($theme->getColorPrimary()); ?>">
		<link rel="manifest" href="<?php print_unescaped(image_path($_['appid'], 'manifest.json')); ?>">
		<?php emit_css_loading_tags($_); ?>
		<?php emit_script_loading_tags($_); ?>
		<?php print_unescaped($_['headers']); ?>
	</head>
	<body id="<?php p($_['bodyid']);?>" <?php foreach ($_['enabledThemes'] as $themeId) {
				p("data-theme-$themeId ");
			}?> data-themes=<?php p(join(',', $_['enabledThemes'])) ?>>
	<?php include 'layout.noscript.warning.php'; ?>

		<?php foreach ($_['initialStates'] as $app => $initialState) { ?>
			<input type="hidden" id="initial-state-<?php p($app); ?>" value="<?php p(base64_encode($initialState)); ?>">
		<?php }?>

		<a href="#app-content" class="button primary skip-navigation skip-content"><?php p($l->t('Skip to main content')); ?></a>
		<a href="#app-navigation" class="button primary skip-navigation"><?php p($l->t('Skip to navigation of app')); ?></a>

		<div id="notification-container">
			<div id="notification"></div>
		</div>
		<header role="banner" id="header">
			<div class="header-left">
				<a href="<?php print_unescaped($_['logoUrl'] ?: link_to('', 'index.php')); ?>"
					id="nextcloud">
					<div class="logo logo-icon">
						<h1 class="hidden-visually">
							<?php p($theme->getName()); ?> <?php p(!empty($_['application'])?$_['application']: $l->t('Apps')); ?>
						</h1>
					</div>
				</a>

				<ul id="appmenu">
					<?php foreach ($_['navigation'] as $entry): ?>
						<li data-id="<?php p($entry['id']); ?>" class="hidden" tabindex="-1">
							<a href="<?php print_unescaped($entry['href']); ?>"
								<?php if (isset($entry['target']) && $entry['target']): ?> target="_blank" rel="noreferrer noopener"<?php endif; ?>
								<?php if ($entry['active']): ?> class="active"<?php endif; ?>
								aria-label="<?php p($entry['name']); ?>">
									<svg width="24" height="20" viewBox="0 0 24 20" alt=""<?php if ($entry['unread'] !== 0) { ?> class="has-unread"<?php } ?>>
										<defs>
											<mask id="hole">
												<rect width="100%" height="100%" fill="white"/>
												<circle r="4.5" cx="21" cy="3" fill="black"/>
											</mask>
										</defs>
										<image x="2" y="0" width="20" height="20" preserveAspectRatio="xMinYMin meet" xlink:href="<?php print_unescaped($entry['icon'] . '?v=' . $_['versionHash']); ?>" style="<?php if ($entry['unread'] !== 0) { ?>mask: url("#hole");<?php } ?>" class="app-icon"></image>
										<circle class="app-icon-notification" r="3" cx="21" cy="3" fill="red"/>
									</svg>
								<div class="unread-counter" aria-hidden="true"><?php p($entry['unread']); ?></div>
								<span>
									<?php p($entry['name']); ?>
								</span>
							</a>
						</li>
					<?php endforeach; ?>
					<li id="more-apps" class="menutoggle"
						aria-haspopup="true" aria-controls="navigation" aria-expanded="false">
						<a href="#" aria-label="<?php p($l->t('More apps')); ?>">
							<div class="icon-more-white"></div>
							<span><?php p($l->t('More')); ?></span>
						</a>
					</li>
				</ul>

				<nav role="navigation">
					<div id="navigation" style="display: none;"  aria-label="<?php p($l->t('More apps menu')); ?>">
						<div id="apps">
							<ul>
								<?php foreach ($_['navigation'] as $entry): ?>
									<li data-id="<?php p($entry['id']); ?>">
									<a href="<?php print_unescaped($entry['href']); ?>"
										<?php if (isset($entry['target']) && $entry['target']): ?> target="_blank" rel="noreferrer noopener"<?php endif; ?>
										<?php if ($entry['active']): ?> class="active"<?php endif; ?>
										aria-label="<?php p($entry['name']); ?>">
										<svg width="20" height="20" viewBox="0 0 16 16" alt=""<?php if ($entry['unread'] !== 0) { ?> class="has-unread"<?php } ?>>
											<defs>
												<filter id="invertMenuMore-<?php p($entry['id']); ?>"><feColorMatrix in="SourceGraphic" type="matrix" values="-1 0 0 0 1 0 -1 0 0 1 0 0 -1 0 1 0 0 0 1 0"></feColorMatrix></filter>
												<mask id="hole">
													<rect width="100%" height="100%" fill="white"/>
													<circle r="4.5" cx="17" cy="3" fill="black"/>
												</mask>
											</defs>
											<image x="0" y="0" width="16" height="16" preserveAspectRatio="xMinYMin meet" filter="url(#invertMenuMore-<?php p($entry['id']); ?>)" xlink:href="<?php print_unescaped($entry['icon'] . '?v=' . $_['versionHash']); ?>" style="<?php if ($entry['unread'] !== 0) { ?>mask: url("#hole");<?php } ?>" class="app-icon"></image>
											<circle class="app-icon-notification" r="3" cx="17" cy="3" fill="red"/>
										</svg>
										<div class="unread-counter" aria-hidden="true"><?php p($entry['unread']); ?></div>
										<span class="app-title"><?php p($entry['name']); ?></span>
									</a>
									</li>
								<?php endforeach; ?>
							</ul>
						</div>
					</div>
				</nav>

			</div>

			<div class="header-right">
				<div id="notifications"></div>
				<div id="unified-search"></div>
				<div id="contactsmenu">
					<div class="icon-contacts menutoggle" tabindex="0" role="button"
					aria-haspopup="true" aria-controls="contactsmenu-menu" aria-expanded="false">
						<span class="hidden-visually"><?php p($l->t('Contacts'));?></span>
					</div>
					<div id="contactsmenu-menu" class="menu"
						aria-label="<?php p($l->t('Contacts menu'));?>"></div>
				</div>
				<div id="settings">
					<div id="expand" tabindex="0" role="button" class="menutoggle"
						aria-label="<?php p($l->t('Settings'));?>"
						aria-haspopup="true" aria-controls="expanddiv" aria-expanded="false">
						<div id="avatardiv-menu" class="avatardiv<?php if ($_['userAvatarSet']) {
				print_unescaped(' avatardiv-shown');
			} else {
				print_unescaped('" style="display: none');
			} ?>"
							 data-user="<?php p($_['user_uid']); ?>"
							 data-displayname="<?php p($_['user_displayname']); ?>"
			<?php
			if ($_['userAvatarSet']) {
				$avatar32 = $getUserAvatar(32); ?> data-avatar="<?php p($avatar32); ?>"
			<?php
			} ?>>
							<?php
							if ($_['userAvatarSet']) {?>
								<img alt="" width="32" height="32"
								src="<?php p($avatar32);?>"
								srcset="<?php p($getUserAvatar(64));?> 2x, <?php p($getUserAvatar(128));?> 4x"
								>
							<?php } ?>
						</div>
					</div>
					<nav class="settings-menu" id="expanddiv" style="display:none;"
						aria-label="<?php p($l->t('Settings menu'));?>">
					<ul>
					<?php foreach ($_['settingsnavigation'] as $entry):?>
						<li data-id="<?php p($entry['id']); ?>">
							<a href="<?php print_unescaped($entry['href']); ?>"
								<?php if ($entry["active"]): ?> class="active"<?php endif; ?>>
								<img alt="" src="<?php print_unescaped($entry['icon'] . '?v=' . $_['versionHash']); ?>">
								<?php p($entry['name']) ?>
							</a>
						</li>
					<?php endforeach; ?>
					</ul>
					</nav>
				</div>
			</div>
		</header>

		<div id="sudo-login-background" class="hidden"></div>
		<form id="sudo-login-form" class="hidden" method="POST">
			<label>
				<?php p($l->t('This action requires you to confirm your password')); ?><br/>
				<input type="password" class="question" autocomplete="new-password" name="question" value=" <?php /* Hack against browsers ignoring autocomplete="off" */ ?>"
				placeholder="<?php p($l->t('Confirm your password')); ?>" />
			</label>
			<input class="confirm" value="<?php p($l->t('Confirm')); ?>" type="submit">
		</form>

		<div id="content" class="app-<?php p($_['appid']) ?>" role="main">
			<?php print_unescaped($_['content']); ?>
		</div>
		<div id="profiler-toolbar"></div>
	</body>
</html>
