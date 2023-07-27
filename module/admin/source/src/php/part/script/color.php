<script>
	ENGINE.color = {
		primary: "#3b7ddd",
		primary_hover: "#326abc",
		primary_light: "rgba(59, 125, 221, 0.15)",
		secondary: "#6c757d",
		secondary_hover: "#686f75",
		secondary_light: "hsla(208, 7%, 46%, 0.15)",

		success: "#1cbb8c",
		success_hover: "#189f77",
		success_light: "rgba(28, 187, 140, 0.15)",
		info: "#17a2b8",
		info_hover: "#148a9c",
		info_light: "rgba(23, 162, 184, 0.15)",
		warning: "#fcb92c",
		warning_hover: "#d69d25",
		warning_light: "rgba(252, 185, 44, 0.15)",
		error: "#dc3545",
		error_hover: "#bb2d3b",
		error_light: "rgba(220, 53, 69, 0.15)",

		body: "#f5f7fb",
		box: "#fff",

		heading: "#000",
		heading_inverse: "#fff",
		subheading: "#939ba2",
		subheading_inverse: "#d3d5d8",
		text: "#495057",
		text_inverse: "#bdc0c5",
		text_muted: "#939ba2",
		link: "#3b7ddd",
		link_hover: "#2f64b1",
		border: "#dee2e6",
		border_hover: "#ced4da"
	};

	if (typeof DATA_THEME !== 'undefined' && DATA_THEME.getTheme() && DATA_THEME.getTheme() === 'dark') {
		ENGINE.color.body = "#19222c";
		ENGINE.color.box = "#222e3c";

		ENGINE.color.heading = "#fff";
		ENGINE.color.heading_inverse = "#000";
		ENGINE.color.subheading = "#d3d5d8";
		ENGINE.color.subheading_inverse = "#939ba2";
		ENGINE.color.text = "#bdc0c5";
		ENGINE.color.text_inverse = "#495057";
		ENGINE.color.text_muted = "#6c757d";
		ENGINE.color.border = "#4e5863";
		ENGINE.color.border_hover = "#384350";
	}
</script>
