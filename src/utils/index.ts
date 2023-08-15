import type { Data, Inputs, AttributeVal, HtmlAttributes } from '../types';

function filterArgs(
  args: Inputs,
  selectedArgs?: string[],
  inverse: boolean = false,
) {
  if (!selectedArgs) return {};

  return Object.keys(args)
    .filter((key) =>
      inverse ? !selectedArgs.includes(key) : selectedArgs.includes(key),
    )
    .reduce((obj, key) => {
      obj[key] = args[key];
      return obj;
    }, {});
}

// Add all required search params with user inputs as values
export function formatUrl(url: string, params?: string[], args?: Inputs) {
  if (!params || !args) return url;

  const newUrl = new URL(url);

  params.forEach((param: string) => {
    if (args[param]) newUrl.searchParams.set(param, args[param]);
  });

  return newUrl.toString();
}

// Construct HTML element and include all default attributes and user-inputted attributes
export function createHtml(
  element: string,
  attributes?: HtmlAttributes,
  htmlAttrArgs?: Inputs,
  urlQueryParamArgs?: Inputs,
) {
  if (!attributes) return `<${element}></${element}>`;

  const formattedAttributes =
    attributes.src?.url && attributes.src?.params
      ? {
          ...attributes,
          src: formatUrl(
            attributes.src.url,
            attributes.src.params,
            urlQueryParamArgs,
          ),
        }
      : attributes;

  const htmlAttributes = Object.keys({
    ...formattedAttributes,
    ...htmlAttrArgs,
  }).reduce((acc, name) => {
    const userVal = htmlAttrArgs?.[name];
    const defaultVal = formattedAttributes[name];
    const finalVal = userVal ?? defaultVal; // overwrite

    const attrString =
      (finalVal as AttributeVal) === true ? name : `${name}="${finalVal}"`;

    return finalVal ? acc + ` ${attrString}` : acc;
  }, '');

  return `<${element}${htmlAttributes}></${element}>`;
}

// Format JSON by including all default and user-required parameters
export function formatData(data: Data, args: Inputs) {
  const allScriptParams = data.scripts?.reduce(
    (acc, script) => [
      ...acc,
      ...(Array.isArray(script.params) ? script.params : []),
    ],
    [] as string[],
  );

  // First, find all input arguments that map to parameters passed to script URLs
  const scriptUrlParamInputs = filterArgs(args, allScriptParams);

  // Second, find all input arguments that map to parameters passed to the HTML src attribute
  const htmlUrlParamInputs = filterArgs(
    args,
    data.html?.attributes.src?.params,
  );

  // Lastly, all remaining arguments are used as separate HTML attributes
  const htmlAttrInputs = filterArgs(
    args,
    [...Object.keys(scriptUrlParamInputs), ...Object.keys(htmlUrlParamInputs)],
    true,
  );

  return {
    ...data,
    // Pass any custom attributes to HTML content
    html: data.html
      ? createHtml(
          data.html.element,
          data.html.attributes,
          htmlAttrInputs,
          htmlUrlParamInputs,
        )
      : null,
    // Pass any required query params with user values for relevant scripts
    scripts: data.scripts
      ? data.scripts.map((script) => ({
          ...script,
          url: formatUrl(script.url, script.params, scriptUrlParamInputs),
        }))
      : null,
  };
}
