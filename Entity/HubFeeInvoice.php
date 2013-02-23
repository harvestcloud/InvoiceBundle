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
 * HubFeeInvoice Entity
 *
 * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
 * @since  2012-05-07
 *
 * @ORM\Entity
 */
class HubFeeInvoice extends Invoice
{
    /**
     * @ORM\ManyToOne(targetEntity="\HarvestCloud\CoreBundle\Entity\Profile", inversedBy="hubFeeInvoicesAsHub")
     * @ORM\JoinColumn(name="hub_id", referencedColumnName="id")
     */
    protected $hub;

    /**
     * @ORM\ManyToOne(targetEntity="\HarvestCloud\CoreBundle\Entity\Profile", inversedBy="hubFeeInvoicesAsSeller")
     * @ORM\JoinColumn(name="seller_id", referencedColumnName="id")
     */
    protected $seller;

    /**
     * @ORM\OneToOne(targetEntity="HarvestCloud\CoreBundle\Entity\Order", mappedBy="hubFeeInvoice")
     */
    protected $orderAsHubFeeInvoice;

    /**
     * Set hub
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-02-22
     *
     * @param  \HarvestCloud\CoreBundle\Entity\Profile $hub
     *
     * @return HubFeeInvoice
     */
    public function setHub(\HarvestCloud\CoreBundle\Entity\Profile $hub = null)
    {
        $this->hub = $hub;

        return $this;
    }

    /**
     * Get hub
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-02-22
     *
     * @return \HarvestCloud\CoreBundle\Entity\Profile
     */
    public function getHub()
    {
        return $this->hub;
    }

    /**
     * Set seller
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-02-22
     *
     * @param  \HarvestCloud\CoreBundle\Entity\Profile $seller
     *
     * @return HubFeeInvoice
     */
    public function setSeller(\HarvestCloud\CoreBundle\Entity\Profile $seller = null)
    {
        $this->seller = $seller;

        return $this;
    }

    /**
     * Get seller
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-02-22
     *
     * @return \HarvestCloud\CoreBundle\Entity\Profile
     */
    public function getSeller()
    {
        return $this->seller;
    }

    /**
     * Set orderAsHubFeeInvoice
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-02-22
     *
     * @param  \HarvestCloud\CoreBundle\Entity\Order $orderAsHubFeeInvoice
     *
     * @return HubFeeInvoice
     */
    public function setOrderAsHubFeeInvoice(\HarvestCloud\CoreBundle\Entity\Order $orderAsHubFeeInvoice = null)
    {
        $this->orderAsHubFeeInvoice = $orderAsHubFeeInvoice;

        return $this;
    }

    /**
     * Get orderAsHubFeeInvoice
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-02-22
     *
     * @return \HarvestCloud\CoreBundle\Entity\Order
     */
    public function getOrderAsHubFeeInvoice()
    {
        return $this->orderAsHubFeeInvoice;
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
//        $sellerJournal = new SellerHubFeeInvoiceJournal($this);
//        $sellerJournal->post();

//        $this->addJournal($sellerJournal);

        // Hub Journal entry
        $hubJournal = new \HarvestCloud\DoubleEntryBundle\Entity\Journal\HubHubFeeInvoiceJournal($this);
        $hubJournal->post();

        $this->addJournal($hubJournal);
    }
}
