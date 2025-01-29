<?php
/**
 * @link https://github.com/yiimaker/yii2-social-share
 * @copyright Copyright (c) 2017-2019 Yii Maker
 * @license BSD 3-Clause License
 */

namespace ymaker\social\share\drivers;

use ymaker\social\share\base\AbstractDriver;

/**
 * Driver for Facebook.
 *
 * @link https://facebook.com
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 * @since 1.0
 */
class Facebook extends AbstractDriver
{
    /**
     * {@inheritdoc}
     */
    protected function processShareData()
    {
        $this->url = static::encodeData($this->url);
    }

    /**
     * {@inheritdoc}
     */
    protected function buildLink()
    {
        return 'https://www.facebook.com/dialog/share?display=popup&redirect_uri=https%3A%2F%2Ffacebook.com&app_id=310887836737434&href={url}';
    }

    /**
     * @return array
     */
    protected function getMetaTags()
    {
        return [
            ['property' => 'og:url',         'content' => '{url}'],
            ['property' => 'og:type',        'content' => 'website'],
            ['property' => 'og:title',       'content' => '{title}'],
            ['property' => 'og:description', 'content' => '{description}'],
            ['property' => 'og:image',       'content' => '{imageUrl}'],
        ];
    }
}
