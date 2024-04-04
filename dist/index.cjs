'use strict';

const id$3 = "google-analytics";
const description$3 = "Install a Google Analytics tag on your website";
const website$3 = "https://analytics.google.com/analytics/web/";
const scripts$2 = [
	{
		url: "https://www.googletagmanager.com/gtag/js",
		params: [
			"id"
		],
		strategy: "worker",
		location: "head",
		action: "append",
		key: "gtag"
	},
	{
		code: "window.dataLayer=window.dataLayer||[];window.gtag=function gtag(){window.dataLayer.push(arguments);};gtag('js',new Date());gtag('config','{{id}}')",
		strategy: "worker",
		location: "head",
		action: "append",
		key: "setup"
	}
];
const data$3 = {
	id: id$3,
	description: description$3,
	website: website$3,
	scripts: scripts$2
};

function isExternalScript(script) {
  return script.url !== void 0;
}

function filterArgs(args, selectedArgs, inverse = false) {
  if (!selectedArgs)
    return {};
  return Object.keys(args).filter(
    (key) => inverse ? !selectedArgs.includes(key) : selectedArgs.includes(key)
  ).reduce((obj, key) => {
    obj[key] = args[key];
    return obj;
  }, {});
}
function formatUrl(url, params, args, slug) {
  const newUrl = slug && Object.keys(slug).length > 0 ? new URL(Object.values(slug)[0], url) : new URL(url);
  if (params && args) {
    params.forEach((param) => {
      if (args[param])
        newUrl.searchParams.set(param, args[param]);
    });
  }
  return newUrl.toString();
}
function formatCode(code, args) {
  return code.replace(/{{(.*?)}}/g, (match) => {
    return args?.[match.split(/{{|}}/).filter(Boolean)[0]];
  });
}
function createHtml(element, attributes, htmlAttrArgs, urlQueryParamArgs, slugParamArg) {
  if (!attributes)
    return `<${element}></${element}>`;
  const formattedAttributes = attributes.src?.url ? {
    ...attributes,
    src: formatUrl(
      attributes.src.url,
      attributes.src.params,
      urlQueryParamArgs,
      slugParamArg
    )
  } : attributes;
  const htmlAttributes = Object.keys({
    ...formattedAttributes,
    ...htmlAttrArgs
  }).reduce((acc, name) => {
    const userVal = htmlAttrArgs?.[name];
    const defaultVal = formattedAttributes[name];
    const finalVal = userVal ?? defaultVal;
    const attrString = finalVal === true ? name : `${name}="${finalVal}"`;
    return finalVal ? acc + ` ${attrString}` : acc;
  }, "");
  return `<${element}${htmlAttributes}></${element}>`;
}
function formatData(data, args) {
  const allScriptParams = data.scripts?.reduce(
    (acc, script) => [
      ...acc,
      ...Array.isArray(script.params) ? script.params : []
    ],
    []
  );
  const scriptUrlParamInputs = filterArgs(args, allScriptParams);
  const htmlUrlParamInputs = filterArgs(
    args,
    data.html?.attributes.src?.params
  );
  const htmlSlugParamInput = filterArgs(args, [
    data.html?.attributes.src?.slugParam
  ]);
  const htmlAttrInputs = filterArgs(
    args,
    [
      ...Object.keys(scriptUrlParamInputs),
      ...Object.keys(htmlUrlParamInputs),
      ...Object.keys(htmlSlugParamInput)
    ],
    true
  );
  return {
    ...data,
    // Pass any custom attributes to HTML content
    html: data.html ? createHtml(
      data.html.element,
      data.html.attributes,
      htmlAttrInputs,
      htmlUrlParamInputs,
      htmlSlugParamInput
    ) : void 0,
    // Pass any required query params with user values for relevant scripts
    scripts: data.scripts ? data.scripts.map((script) => {
      return isExternalScript(script) ? {
        ...script,
        url: formatUrl(script.url, script.params, scriptUrlParamInputs)
      } : {
        ...script,
        code: formatCode(script.code, scriptUrlParamInputs)
      };
    }) : void 0
  };
}

const GoogleAnalytics = ({ ...args }) => {
  return formatData(data$3, args);
};

const id$2 = "google-tag-manager";
const description$2 = "Install Google Tag Manager on your website";
const website$2 = "https://developers.google.com/tag-platform/tag-manager/web";
const scripts$1 = [
	{
		url: "https://www.googletagmanager.com/gtm.js",
		params: [
			"id"
		],
		strategy: "worker",
		location: "head",
		action: "append",
		key: "gtm"
	},
	{
		code: "window.dataLayer=window.dataLayer||[];window.dataLayer.push({'gtm.start':new Date().getTime(),event:'gtm.js'});",
		strategy: "worker",
		location: "head",
		action: "append",
		key: "setup"
	}
];
const data$2 = {
	id: id$2,
	description: description$2,
	website: website$2,
	scripts: scripts$1
};

const GoogleTagManager = ({ ...args }) => {
  return formatData(data$2, args);
};

const id$1 = "google-maps-embed";
const description$1 = "Embed a Google Maps embed on your webpage";
const website$1 = "https://developers.google.com/maps/documentation/embed/get-started";
const html$1 = {
	element: "iframe",
	attributes: {
		loading: "lazy",
		src: {
			url: "https://www.google.com/maps/embed/v1/place",
			slugParam: "mode",
			params: [
				"key",
				"q",
				"center",
				"zoom",
				"maptype",
				"language",
				"region"
			]
		},
		referrerpolicy: "no-referrer-when-downgrade",
		frameborder: "0",
		style: "border:0",
		allowfullscreen: true,
		width: null,
		height: null
	}
};
const data$1 = {
	id: id$1,
	description: description$1,
	website: website$1,
	html: html$1
};

const GoogleMapsEmbed = ({ ...args }) => {
  return formatData(data$1, args);
};

const id = "youtube-embed";
const description = "Embed a YouTube embed on your webpage.";
const website = "https://github.com/paulirish/lite-youtube-embed";
const html = {
	element: "lite-youtube",
	attributes: {
		videoid: null,
		playlabel: null
	}
};
const stylesheets = [
	"https://cdn.jsdelivr.net/gh/paulirish/lite-youtube-embed@master/src/lite-yt-embed.css"
];
const scripts = [
	{
		url: "https://cdn.jsdelivr.net/gh/paulirish/lite-youtube-embed@master/src/lite-yt-embed.js",
		strategy: "idle",
		location: "head",
		action: "append",
		key: "lite-yt-embed"
	}
];
const data = {
	id: id,
	description: description,
	website: website,
	html: html,
	stylesheets: stylesheets,
	scripts: scripts
};

const YouTubeEmbed = ({ ...args }) => {
  return formatData(data, args);
};

exports.GoogleAnalytics = GoogleAnalytics;
exports.GoogleMapsEmbed = GoogleMapsEmbed;
exports.GoogleTagManager = GoogleTagManager;
exports.YouTubeEmbed = YouTubeEmbed;
exports.isExternalScript = isExternalScript;
