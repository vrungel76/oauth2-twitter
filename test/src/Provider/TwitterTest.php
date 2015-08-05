<?php namespace League\OAuth2\Client\Test\Provider;

use League\OAuth2\Client\Provider\Twitter;
use Mockery as m;

class TwitterTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Provider
	 *
	 * @var Twitter
	 */
	protected $provider;

	protected function setUp()
	{
		$this->provider = new Twitter([
			'clientId' => 'mock_client_id',
			'clientSecret' => 'mock_secret',
			'redirectUri' => 'none',
		]);
	}

	public function tearDown()
	{
		m::close();
		parent::tearDown();
	}

	public function testAuthorizationUrl() {
		$url = $this->provider->getAuthorizationUrl();
		$uri = parse_url($url);
		parse_str($uri['query'], $query);

		$this->assertArrayHasKey('client_id', $query);
		$this->assertArrayHasKey('redirect_uri', $query);
		$this->assertArrayHasKey('state', $query);
		$this->assertArrayHasKey('scope', $query);
		$this->assertArrayHasKey('response_type', $query);
		$this->assertArrayHasKey('approval_prompt', $query);
		$this->assertNotNull($this->provider->getState());
	}

    public function testScopes()
    {
        $options = ['scope' => [uniqid(),uniqid()]];
        $url = $this->provider->getAuthorizationUrl($options);
        $this->assertContains(urlencode(implode(',', $options['scope'])), $url);
    }

    /**
     * @link https://dev.twitter.com/oauth/reference/get/oauth/authorize
     */
    public function testGetAuthorizationUrl()
    {
        $url = $this->provider->getAuthorizationUrl();
        $uri = parse_url($url);
        $this->assertEquals('/oauth/authorize', $uri['path']);
    }

    /**
     * @link https://dev.twitter.com/oauth/reference/post/oauth/access_token
     */
    public function testGetBaseAccessTokenUrl()
    {
        $params = [];
        $url = $this->provider->getBaseAccessTokenUrl($params);
        $uri = parse_url($url);
        $this->assertEquals('/oauth/access_token', $uri['path']);
    }
}
