<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../register-page/model/validator.php';

class ValidatorTest extends TestCase
{
    public function testValidEmail()
    {
        $result = Validator::isValidEmail('test@example.com');
        $this->assertTrue($result, 'Standard email should be valid');
    }

    public function testInvalidEmail()
    {
        $result = Validator::isValidEmail('not-an-email');
        $this->assertFalse($result, 'String without @ should be invalid');
    }

    public function testPasswordsMatch()
    {
        $this->assertTrue(Validator::doPasswordsMatch('secret', 'secret'));
        
        $this->assertFalse(Validator::doPasswordsMatch('secret', 'wrongpass'));
    }

    public function testPasswordLength()
    {
        $this->assertFalse(Validator::isStrongPassword('123'), 'Password too short');
        
        $this->assertTrue(Validator::isStrongPassword('123456'), 'Password long enough');
    }
}
?>