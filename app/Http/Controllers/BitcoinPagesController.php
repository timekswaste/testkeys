<?php

namespace App\Http\Controllers;

use App\Crypto\BitcoinPageKeys;
use App\Crypto\BitcoinPageNumber;

class BitcoinPagesController extends Controller
{
    public function index($pageNumber = null)
    {
        $btcPageNumber = new BitcoinPageNumber($pageNumber);

        if ($btcPageNumber->shouldRedirect()) {
            return $btcPageNumber->redirect();
        }

        $keys = BitcoinPageKeys::generate($pageNumber);

        return view('bitcoin-page', [
            'pageNumber'          => $pageNumber,
            'nextPage'            => increment_string($pageNumber),
            'previousPage'        => decrement_string($pageNumber),
            'lastPage'            => config('keys.bitcoin-max-page'),
            'isOnFirstPage'       => $pageNumber === '1',
            'isOnLastPage'        => $pageNumber === config('keys.bitcoin-max-page'),
            'isShortNumberString' => $btcPageNumber->isShortNumberString(),
            'isSmallNumber'       => $btcPageNumber->isSmallNumber(),
            'keys'                => json_encode($keys),
        ]);
    }

    public function randomPage()
    {
        $randomPageNumber = BitcoinPageNumber::random();

        return redirect()->route('btcPages', $randomPageNumber);
    }

    public function invalidPage()
    {
        return 'invalid page yo';
    }
}
