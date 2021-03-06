<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet
	version="1.0"
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	xmlns:exslt-common="http://exslt.org/common"
	xmlns:saxon="http://icl.com/saxon"
	xmlns:envelope_0_11="http://youds.com/youds/1.0/config"
	xmlns:envelope_1_0="http://youds.com/youds/config/global/envelope/1.0"
	xmlns:envelope_1_1="http://youds.com/youds/config/global/envelope/1.1"
>
	
	<xsl:variable name="envelope_0_11" select="'http://youds.com/youds/1.0/config'" />
	<xsl:variable name="envelope_1_0" select="'http://youds.com/youds/config/global/envelope/1.0'" />
	<xsl:variable name="envelope_1_1" select="'http://youds.com/youds/config/global/envelope/1.1'" />
	
	<!-- callable template for migrating envelope nodes -->
	<xsl:template name="_common-migrate-envelope-element">
		<!-- param for the target namespace; defaults to 1.0 -->
		<xsl:param name="namespace" select="$envelope_1_0" />
		<!-- attributes to insert, defaults to empty node set -->
		<xsl:param name="attributes" select="self::node()[false()]" />
		
		<xsl:call-template name="_common-migrate-element">
			<xsl:with-param name="namespace" select="$namespace" />
			<xsl:with-param name="attributes" select="$attributes" />
		</xsl:call-template>
	</xsl:template>
	
	<xsl:template name="_common-migrate-element">
		<!-- param for the target namespace; no default -->
		<xsl:param name="namespace" />
		<!-- attributes to insert, defaults to empty node set -->
		<xsl:param name="attributes" select="self::node()[false()]" />
		
		<!-- create an element of the same name -->
		<xsl:element name="{local-name()}" namespace="{$namespace}">
			<!-- also copy all namespace declarations with a prefix (so only xmlns:foo="...", not plain xmlns="..."), except the one of the current element (otherwise, we'd overwrite the namespace in the <element> above if it's just xmlns etc) -->
			<!-- the not(name() = '') part is to ensure that we don't copy xmlns="..." declarations, since that might give very strange results and isn't necessary anyway -->
			<!-- the purpose of copying these declarations is to make sure that they remain available as originally declared, which usually is only relevant in cases where element or attribute content refers to the declared prefixes again, think <xs:element type="foo:burp" />. We need that mainly for SOAP, WSDL and stuff like that -->
			<xsl:copy-of select="namespace::*[not(name() = '') and not(. = namespace-uri(current()))]" />
			<xsl:copy-of select="@*" />
			<xsl:copy-of select="exslt-common:node-set($attributes)//@*" />
			<xsl:apply-templates />
		</xsl:element>
	</xsl:template>
	
	<!-- 0.11 to 1.0 -->
	<xsl:template match="envelope_0_11:configurations">
		<xsl:call-template name="_common-migrate-envelope-element" />
	</xsl:template>
	<xsl:template match="envelope_0_11:configuration">
		<xsl:call-template name="_common-migrate-envelope-element" />
	</xsl:template>
	<xsl:template match="envelope_0_11:sandbox">
		<xsl:call-template name="_common-migrate-envelope-element" />
	</xsl:template>
	<xsl:template match="envelope_0_11:parameters">
		<xsl:call-template name="_common-migrate-envelope-element" />
	</xsl:template>
	<xsl:template match="envelope_0_11:parameter">
		<xsl:call-template name="_common-migrate-envelope-element" />
	</xsl:template>
	
	<!-- 1.0 to 1.1 -->
	<xsl:template match="envelope_1_0:configurations">
		<xsl:call-template name="_common-migrate-envelope-element">
			<xsl:with-param name="namespace" select="$envelope_1_1" />
		</xsl:call-template>
	</xsl:template>
	<xsl:template match="envelope_1_0:configuration">
		<xsl:call-template name="_common-migrate-envelope-element">
			<xsl:with-param name="namespace" select="$envelope_1_1" />
		</xsl:call-template>
	</xsl:template>
	<xsl:template match="envelope_1_0:sandbox">
		<xsl:call-template name="_common-migrate-envelope-element">
			<xsl:with-param name="namespace" select="$envelope_1_1" />
		</xsl:call-template>
	</xsl:template>
	<xsl:template match="envelope_1_0:parameters">
		<xsl:call-template name="_common-migrate-envelope-element">
			<xsl:with-param name="namespace" select="$envelope_1_1" />
		</xsl:call-template>
	</xsl:template>
	<xsl:template match="envelope_1_0:parameter">
		<xsl:call-template name="_common-migrate-envelope-element">
			<xsl:with-param name="namespace" select="$envelope_1_1" />
		</xsl:call-template>
	</xsl:template>
	
	<!-- we need to apply templates to sub-elements, just in case someone wrapped a native youds element and processed that with xsl, for example -->
	<!-- so we cannot use copy-of here -->
	<!-- node() and the copy will mean that everything is copied, even text nodes etc -->
	<xsl:template match="node()|@*">
		<xsl:copy>
			<xsl:apply-templates select="node()|@*"/>
		</xsl:copy>
	</xsl:template>
	
</xsl:stylesheet>
