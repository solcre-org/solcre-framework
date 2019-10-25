<?php

namespace SolcreFrameworkTest;

use MegaPharma\V1\Domain\test\Bootstrap;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;
use Solcre\SolcreFramework2\Entity\EmailAddress;
use Solcre\SolcreFramework2\Service\EmailService;

class EmailTest extends TestCase
{
    /* @var $emailService EmailService */
    private $emailService;
    private $configMegaPharmaEmail;

    protected function setUp()
    {
        $this->emailService = Bootstrap::getServiceManager()->get(EmailService::class);
        $this->configMegaPharmaEmail = Bootstrap::getServiceManager()->get('config')['MegaPharma']['EMAILS'];
    }

    public function testEmailServiceInstance(): void
    {
        $this->assertInstanceOf(EmailService::class, $this->emailService);
    }

    public function testCreateAddressesWithInvalidEmailType()
    {
        $invalidEmails = [
            [
                'email' => 'jhon.doe@solcre.com',
                'type'  => 99,
            ]
        ];

        $addresses = $this->emailService->generateAddresses($invalidEmails);
        $this->assertEquals([], $addresses);
    }

    public function testCreateAddressWithValidEmailType()
    {
        $invalidEmails = [
            [
                'email' => 'jhon.doe@solcre.com',
                'type'  => 2,
            ]
        ];

        $addresses = $this->emailService->generateAddresses($invalidEmails);
        $this->assertEquals(
            [
                new EmailAddress('jhon.doe@solcre.com', null, 2)
            ],
            $addresses
        );
    }

    public function testCreateAddressesWithValidEmailTypeAndInvalid()
    {
        $invalidEmails = [
            [
                'email' => 'jhon.doe@solcre.com',
                'type'  => 2,
            ],
            [
                'email' => 'jhon.doe1@solcre.com',
                'type'  => 99,
            ]
        ];

        $addresses = $this->emailService->generateAddresses($invalidEmails);
        $this->assertEquals(
            [
                new EmailAddress('jhon.doe@solcre.com', null, 2)
            ],
            $addresses
        );
    }

    public function testCreateAddressesWithValidEmailType()
    {
        $invalidEmails = [
            [
                'email' => 'jhon.doe@solcre.com',
                'name'  => 'Jhon',
                'type'  => 2,
            ],
            [
                'email' => 'jhon.doe2@solcre.com',
                'type'  => 4,
            ]
        ];

        $addresses = $this->emailService->generateAddresses($invalidEmails);
        $this->assertEquals(
            [
                new EmailAddress('jhon.doe@solcre.com', 'Jhon', 2),
                new EmailAddress('jhon.doe2@solcre.com', null, 4)
            ],
            $addresses
        );
    }

    public function testCreateFromEmailWithEmptyParam()
    {
        $from = $this->emailService->getFromEmail();
        $this->assertEquals(new EmailAddress($this->configMegaPharmaEmail['DEFAULT_FROM_EMAIL'], null, 1), $from);
    }

    public function testCreateFromEmailWithParam()
    {
        $from = $this->emailService->getFromEmail('jhon.doe@solcre.com');
        $this->assertEquals(new EmailAddress('jhon.doe@solcre.com', null, 1), $from);
    }

    public function testMergeDataWithDefaultVariables()
    {
        $templateData = [
            'foo'  => 'bar',
            'foo2' => 'bar2'
        ];
        $method = new ReflectionMethod($this->emailService, 'mergeDefaultVariables');
        $method->setAccessible(true);
        $expected = [
            'images_path' => $this->configMegaPharmaEmail['ASSETS_PATH'],
            'foo'         => 'bar',
            'foo2'        => 'bar2'
        ];
        $this->assertEquals($expected, $method->invokeArgs($this->emailService, [&$templateData]));
    }

    public function testMergeDataWithEmptyVariableData()
    {
        $templateData = [];
        $method = new ReflectionMethod($this->emailService, 'mergeDefaultVariables');
        $method->setAccessible(true);
        $expected = [
            'images_path' => $this->configMegaPharmaEmail['ASSETS_PATH'],
        ];
        $this->assertEquals($expected, $method->invokeArgs($this->emailService, [&$templateData]));
    }
}
