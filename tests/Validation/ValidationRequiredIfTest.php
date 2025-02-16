<?php

namespace Illuminate\Tests\Validation;

use Exception;
use Illuminate\Validation\Rules\RequiredIf;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class ValidationRequiredIfTest extends TestCase
{
    public function testItClousureReturnsFormatsAStringVersionOfTheRule()
    {
        $rule = new RequiredIf(function () {
            return true;
        });

        $this->assertSame('required', (string) $rule);

        $rule = new RequiredIf(function () {
            return false;
        });

        $this->assertSame('', (string) $rule);

        $rule = new RequiredIf(true);

        $this->assertSame('required', (string) $rule);

        $rule = new RequiredIf(false);

        $this->assertSame('', (string) $rule);
    }

    public function testItOnlyCallableAndBooleanAreAcceptableArgumentsOfTheRule()
    {
        $rule = new RequiredIf(false);

        $rule = new RequiredIf(true);

        $this->expectException(InvalidArgumentException::class);

        $rule = new RequiredIf('phpinfo');
    }

    public function testItReturnedRuleIsNotSerializable()
    {
        $this->expectException(Exception::class);

        $rule = serialize(new RequiredIf(function () {
            return true;
        }));
    }

    public function testThatNoArrayToStringErrorExceptionIsThrownWhenDealingWithDependantBooleanField()
    {
        $validator = \Illuminate\Support\Facades\Validator::make([
            'boolean_input' => ['test'],
            'dependant_input' => null,
        ], [
            'boolean_input' => 'required|boolean',
            'dependant_input' => 'required_if:boolean_input,true',
        ]);

        $this->assertIsBool($validator->fails());
    }
}
