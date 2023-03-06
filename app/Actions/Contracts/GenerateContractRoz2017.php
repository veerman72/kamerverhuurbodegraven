<?php

namespace App\Actions\Contracts;

use App\Models\Contract;
use App\Services\ContractRoz2017Service;

class GenerateContractRoz2017 extends ContractRoz2017Service
{
    private string $output;

    public function __construct(Contract $contract, string $output = 'I')
    {
        parent::__construct(contract: $contract);
        $this->output = $output;
    }

    public function generate(): string
    {
        //ToDo: contract type/option (in DB?: ROZ2017/2)
        $this->AddPage();
        $this->writePreface();
        $this->writeContractors();
        $this->writeConsideration(); // contract type/option!
        $this->writeAgreed();
        $this->writeArticle_1();
        $this->writeArticle_2();
        $this->writeArticle_3(); // contract type/option!
        $this->writeArticle_4();
        $this->writeArticle_5();
        $this->writeArticle_6();
        $this->writeArticle_7();
        $this->writeArticle_8();
        $this->writeArticle_9();
        $this->writeArticle_10();
        $this->writeArticle_11();
        $this->writeArticle_12();
        $this->writeClosing();

        return $this->Output(dest: $this->output);
    }
}
