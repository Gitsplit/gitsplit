<?php

/*
 * This file is part of the GitSplit package.
 *
 * Copyright (c) 2015 Marc Morera
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 */

namespace Gitsplit\RepositoryBundle\Model;
 
/**
 * Class SplitRuleChain
 */
class SplitRuleChain
{
    /**
     * @var array
     *
     * Split rules
     */
    protected $splitRules;

    /**
     * Add split rule
     *
     * @param SplitRule $splitRule Split rule
     *
     * @return $this Self object
     */
    public function addSplitRule(SplitRule $splitRule)
    {
        $this->splitRules = $splitRule;

        return $this;
    }

    /**
     * Return split rules
     *
     * @return array Split rules
     */
    public function getSplitRules()
    {
        return $this->splitRules;
    }
}
 