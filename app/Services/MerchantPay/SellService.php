<?php
namespace App\Services\MerchantPay;

use Illuminate\Support\Facades\View;

class SellService
{
    /**
     * Generate XML from Blade template
     *
     * @param array $data
     * @return string
     */
    public function generateXml(array $data): string
    {
        // Render the Blade template with the provided data
        return View::make('xml.merchant-pay.sell', $data)->render();
    }
}
