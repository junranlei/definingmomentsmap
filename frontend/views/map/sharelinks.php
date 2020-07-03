<?php
use daxslab\sharelinks\ShareLinks;
use \yii\helpers\Html;

/**
 * @var yii\base\View $this
 */

?>

<div class="socialShareBlock">
	<?=
	Html::a('<i class="icon-facebook-squared">facebook</i>', $this->context->shareUrl(ShareLinks::SOCIAL_FACEBOOK),
		['title' => 'Share to Facebook']) ?>
	<?=
	Html::a('<i class="icon-twitter-squared"></i>', $this->context->shareUrl(ShareLinks::SOCIAL_TWITTER),
		['title' => 'Share to Twitter']) ?>
	<?=
	Html::a('<i class="icon-linkedin-squared"></i>', $this->context->shareUrl(ShareLinks::SOCIAL_LINKEDIN),
		['title' => 'Share to LinkedIn']) ?>
	<?=
	Html::a('<i class="icon-gplus-squared"></i>', $this->context->shareUrl(ShareLinks::SOCIAL_GPLUS),
		['title' => 'Share to Google Plus']) ?>
	<?=
	Html::a('<i class="icon-vkontakte"></i>', $this->context->shareUrl(ShareLinks::SOCIAL_VKONTAKTE),
		['title' => 'Share to Vkontakte']) ?>
	<?=
	Html::a('<i class="icon-tablet"></i>', $this->context->shareUrl(ShareLinks::SOCIAL_KINDLE),
		['title' => 'Send to Kindle']) ?>
</div>