	<?php if (sncmono_footer()) { ?>
		<div id="footer">
			<?php echo sncmono_credit().esc_html($_SESSION['sncmono_options']['footer_text']); ?>
		</div>
	<?php } ?>
</div>

<?php wp_footer(); ?>
</body>
</html>