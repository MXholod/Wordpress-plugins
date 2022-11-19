//alert("Ok");
//console.log("WP ",wp.blocks);
const attrs = {
	//bananaColor: {type: "string", source: "text", selector: ".fruit-one"},
	//kiwiColor: {type: "string", source: "text", selector: ".fruit-two"}
	bananaColor: {type: "string"},
	kiwiColor: {type: "string"}
}
function save(props){
	//return wp.element.createElement("h2", null, "Hi from Wordpress");
	return null; /*(<>
		<h3>There are fruits</h3>
		<div>
			<p>Banana color is <span className="fruit-one">{props.attributes.bananaColor}</span></p>
			<p>Kiwi color is always <span className="fruit-two">{props.attributes.kiwiColor}</span></p>
		</div>
	</>);*/
}

wp.blocks.registerBlockType("myplugin/mx-custom-block-post-plugin",{
	title: "Custom block post",
	icon: "buddicons-activity",
	category: "common",
	attributes: attrs,
	edit: function(props){
		//return wp.element.createElement("h3", null, "Hi this custom block");
		//JSX
		return (<>
			<h3>Type a fruit color</h3>
			<input type="text" placeholder="Banana color" 
				value={props.attributes.bananaColor}
				onChange={(event)=>{
					props.setAttributes({bananaColor: event.target.value});
				}} 
			/>
			<input type="text" placeholder="Kiwi color" 
				value={props.attributes.kiwiColor}
				onChange={(event)=>{
					props.setAttributes({kiwiColor: event.target.value});
				}} 
			/>
		</>);
	},
	save,
	deprecated: [{
		attributes: attrs,
		save
	}]
});