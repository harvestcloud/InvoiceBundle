<?php

/*
 * This file is part of the Harvest Cloud package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HarvestCloud\InvoiceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProductPostingFeeInvoice Entity
 *
 * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
 * @since  2012-05-07
 *
 * @ORM\Entity
 */
class ProductPostingFeeInvoice extends Invoice
{
    /**
     * Exchange
     *
     * @var \HarvestCloud\CoreBundle\Entity\Exchange
     */
     protected $exchange;

    /**
     * @ORM\OneToOne(targetEntity="HarvestCloud\CoreBundle\Entity\Order", mappedBy="productPostingFeeInvoice")
     */
    protected $order;

    /**
     * __construct()
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-03-11
     *
     * @param  \HarvestCloud\CoreBundle\Entity\Order    $order
     * @param  \HarvestCloud\CoreBundle\Entity\Exchange $exchange
     */
    public function __construct(\HarvestCloud\CoreBundle\Entity\Order $order,
      \HarvestCloud\CoreBundle\Entity\Exchange $exchange)
    {
        $this->setOrder($order);
        $this->setExchange($exchange);
    }

    /**
     * Set exchange
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-03-11
     *
     * @param  \HarvestCloud\CoreBundle\Entity\Exchange $exchange
     */
    public function setExchange(\HarvestCloud\CoreBundle\Entity\Exchange $exchange)
    {
        $this->exchange = $exchange;
    }

    /**
     * Get exchange
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-03-11
     *
     * @return \HarvestCloud\CoreBundle\Entity\Exchange
     */
    public function getExchange()
    {
        return $this->exchange;
    }

    /**
     * Set order
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-03-11
     *
     * @param  \HarvestCloud\CoreBundle\Entity\Order $order
     *
     * @return ProductPostingFeeInvoice
     */
    public function setOrder(\HarvestCloud\CoreBundle\Entity\Order $order = null)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get order
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-03-11
     *
     * @return \HarvestCloud\CoreBundle\Entity\Order
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * post()
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-03-11
     */
    public function post()
    {
        $this->setPostedAt(new \DateTime());

        // Seller Journal entry
        $sellerJournal = new \HarvestCloud\DoubleEntryBundle\Entity\Journal\SellerProductPostingFeeInvoiceJournal($this);
        $sellerJournal->post();

        $this->addJournal($sellerJournal);

        // Exchange Journal entry
        $exchangeJournal = new \HarvestCloud\DoubleEntryBundle\Entity\Journal\ExchangeProductPostingFeeInvoiceJournal($this);
        $exchangeJournal->post();

        $this->addJournal($exchangeJournal);
    }
}
