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
 * OrderInvoice Entity
 *
 * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
 * @since  2012-05-07
 *
 * @ORM\Entity
 */
class OrderInvoice extends Invoice
{
    /**
     * @ORM\OneToOne(targetEntity="HarvestCloud\CoreBundle\Entity\Order", mappedBy="invoice")
     */
    protected $order;

    /**
     * __construct()
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.co>
     * @since  2013-02-23
     *
     * @param  \HarvestCloud\CoreBundle\Entity\Order $order
     */
    public function __construct(\HarvestCloud\CoreBundle\Entity\Order $order)
    {
        $this->setOrder($order);
    }

    /**
     * Set order
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-02-23
     *
     * @param  \HarvestCloud\CoreBundle\Entity\Order $order
     *
     * @return OrderInvoice
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
     * @since  2013-02-23
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
     * @since  2013-02-23
     */
    public function post()
    {
        // Seller Journal entry
        $sellerJournal = new \HarvestCloud\DoubleEntryBundle\Entity\Journal\SellerOrderInvoiceJournal($this);
        $sellerJournal->post();

        $this->addJournal($sellerJournal);

        // Buyer Journal entry
        $buyerJournal = new \HarvestCloud\DoubleEntryBundle\Entity\Journal\BuyerOrderInvoiceJournal($this);
        $buyerJournal->post();

        $this->addJournal($buyerJournal);
    }
}
