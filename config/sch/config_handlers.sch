<schema xmlns="http://purl.oclc.org/dsdl/schematron">
	<title>A test schema for YoudsFramework</title>
	<ns prefix="ae" uri="http://youds.com/youds/config/global/envelope/1.1" />
	<ns prefix="ch" uri="http://youds.com/youds/config/parts/config_handlers/1.1" />
	<pattern name="Base structure">
		<rule context="ae:configuration">
			<assert test="ch:handlers">A configuration block contains handlers.</assert>
		</rule>
	</pattern>
</schema>
