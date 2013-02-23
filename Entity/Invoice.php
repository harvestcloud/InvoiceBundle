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
 * Invoice Entity
 *
 * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
 * @since  2012-05-07
 *
 * @ORM\Entity
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({
 *    "Order"             = "OrderInvoice",
 *    "HubFee"            = "HubFeeInvoice",
 *    "ProductPostingFee" = "ProductPostingFeeInvoice"
 * })
 * @ORM\Table(name="invoice")
 */
abstract class Invoice
{
    /**
     * Statuses
     *
     * @var int
     */
    const
        STATUS_POSTED   = 1
    ;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="decimal", scale=2)
     */
    protected $amount = 0;

    /**
     * @ORM\Column(type="integer")
     */
    protected $status_code = self::STATUS_POSTED;

    /**
     * Get id
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-07
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set amount
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-07
     *
     * @param  decimal $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * Get amount
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-07
     *
     * @return decimal
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set status_code
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-07
     *
     * @param integer $statusCode
     */
    public function setStatusCode($statusCode)
    {
        $this->status_code = $statusCode;
    }

    /**
     * Get status_code
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-07
     *
     * @return integer
     */
    public function getStatusCode()
    {
        return $this->status_code;
    }
}
