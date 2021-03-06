<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet
	version="1.0"
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	xmlns:envelope_0_11="http://youds.com/youds/1.0/config"
	xmlns:settings_1_0="http://youds.com/youds/config/parts/settings/1.0"
	xmlns:settings_1_1="http://youds.com/youds/config/parts/settings/1.1"
>
	
	<xsl:output method="xml" version="1.0" encoding="utf-8" indent="yes" />
	
	<xsl:include href="_common.xsl" />
	
	<xsl:variable name="settings_1_0" select="'http://youds.com/youds/config/parts/settings/1.0'" />
	<xsl:variable name="settings_1_1" select="'http://youds.com/youds/config/parts/settings/1.1'" />
	
	<!-- pre-1.0 backwards compatibility for 1.0 -->
	<!-- non-"envelope" elements are copied to the 1.0 settings namespace -->
	<xsl:template match="envelope_0_11:*">
		<xsl:element name="{local-name()}" namespace="{$settings_1_0}">
			<xsl:copy-of select="@*" />
			<xsl:apply-templates />
		</xsl:element>
	</xsl:template>
	
	<!-- 1.0 backwards compatibility for 1.1 -->
	<xsl:template match="settings_1_0:*">
		<xsl:element name="{local-name()}" namespace="{$settings_1_1}">
			<xsl:copy-of select="@*" />
			<xsl:apply-templates />
		</xsl:element>
	</xsl:template>
	
</xsl:stylesheet>
