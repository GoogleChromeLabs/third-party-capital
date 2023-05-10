import type { Data, Inputs, AttributeVal, HtmlAttributes } from '../types';

// Add all required search params with user inputs as values
function formatUrl(url: string, params: string[], args: Inputs) {
  if (!params || !args) return url;

  let newUrl = new URL(url);

  params.forEach((param: string) => {
    if (args[param]) newUrl.searchParams.set(param, args[param]);
  });

  return newUrl.toString();
}

// Construct HTML element and include all default attributes and user-inputted attributes
function createHTML(element: string, attributes: HtmlAttributes, args: Inputs) {
  const formattedAttributes =
    attributes.src?.url && attributes.src?.params
      ? {
          ...attributes,
          src: formatUrl(attributes.src.url, attributes.src.params, args),
        }
      : attributes;

  const htmlAttributes = Object.keys(formattedAttributes).reduce(
    (acc, name) => {
      const userVal = args[name];
      const defaultVal = formattedAttributes[name];
      const finalVal = userVal ?? defaultVal; // overwrite

      const attrString =
        (finalVal as AttributeVal) === true ? name : `${name}="${finalVal}"`;

      return finalVal ? acc + ` ${attrString}` : acc;
    },
    '',
  );

  return `<${element}${htmlAttributes}></${element}>`;
}

// Format JSON by including all default and user-required parameters
export function formatData(data: Data, args: Inputs) {
  return {
    ...data,
    // Pass any custom attributes to HTML content
    html: data.html
      ? createHTML(data.html.element, data.html.attributes, args)
      : null,
    // Pass any required query params with user values for relevant scripts
    scripts: data.scripts
      ? data.scripts.map((script) => ({
          ...script,
          url: formatUrl(script.url, script.params!, args),
        }))
      : null,
  };
}
