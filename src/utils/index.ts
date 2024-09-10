import type {
  Data,
  Inputs,
  AttributeVal,
  HtmlAttributes,
  Output,
} from '../types';
import { isExternalScript } from '../types';

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
    }, {} as Record<string, any>);
}

// Add all required search params with user inputs as values
export function formatUrl(
  url: string,
  paramKeys?: string[],
  paramInputs?: Inputs,
  slug?: Inputs,
  optionalParamKeys?: string[],
  optionalParamInputs?: Inputs,
) {
  const newUrl =
    slug && Object.keys(slug).length > 0
      ? new URL(Object.values(slug)[0], url) // If there's a user inputted param for the URL slug, replace the default existing slug or include it
      : new URL(url);

  if (paramKeys && paramInputs) {
    paramKeys.forEach((param: string) => {
      if (paramInputs[param])
        newUrl.searchParams.set(param, paramInputs[param]);
    });
  }

  if (optionalParamKeys && optionalParamInputs) {
    optionalParamKeys.forEach((param: string) => {
      if (optionalParamInputs[param])
        newUrl.searchParams.set(param, optionalParamInputs[param]);
    });
  }

  return newUrl.toString();
}

export function formatCode(
  code: string,
  paramInputs?: Inputs,
  optionalParamInputs?: Inputs,
) {
  return code.replace(/{{(.*?)}}/g, (match) => {
    const name = match.split(/{{|}}/).filter(Boolean)[0];

    return JSON.stringify(
      paramInputs?.[name] !== undefined
        ? paramInputs?.[name]
        : optionalParamInputs?.[name],
    );
  });
}

// Construct HTML element and include all default attributes and user-inputted attributes
export function createHtml(
  element: string,
  attributes?: HtmlAttributes,
  htmlAttrArgs?: Inputs,
  urlQueryParamArgs?: Inputs,
  slugParamArg?: Inputs,
) {
  if (!attributes) return `<${element}></${element}>`;

  const formattedAttributes: any = attributes.src?.url
    ? {
        ...attributes,
        src: formatUrl(
          attributes.src.url,
          attributes.src.params,
          urlQueryParamArgs,
          slugParamArg,
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
export function formatData(data: Data, args: Inputs): Output {
  const allScriptParams = data.scripts?.reduce(
    (acc, script) => [
      ...acc,
      ...(Array.isArray(script.params) ? script.params : []),
      ...(script.optionalParams ? Object.keys(script.optionalParams) : []),
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

  // Third, find the input argument that maps to the slug parameter passed to the HTML src attribute if present
  const htmlSlugParamInput = filterArgs(args, [
    data.html?.attributes.src?.slugParam!,
  ]);

  // Lastly, all remaining arguments are forwarded as separate HTML attributes
  const htmlAttrInputs = filterArgs(
    args,
    [
      ...Object.keys(scriptUrlParamInputs),
      ...Object.keys(htmlUrlParamInputs),
      ...Object.keys(htmlSlugParamInput),
    ],
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
          htmlSlugParamInput,
        )
      : undefined,
    // Pass any required query params with user values for relevant scripts
    scripts: data.scripts
      ? data.scripts.map((script) => {
          const paramKeys = script.params;
          const paramInputs = filterArgs(args, paramKeys);
          const optionalParamKeys = script.optionalParams
            ? Object.keys(script.optionalParams)
            : [];

          // if we're receiving undefined or null as an ar, we check the optionalParams
          const newArgs: any = { ...script.optionalParams };
          Object.keys(args).forEach((key) => {
            if (args[key]) newArgs[key] = args[key];
          });
          const optionalParamInputs = filterArgs(newArgs, optionalParamKeys);

          return isExternalScript(script)
            ? {
                ...script,
                url: formatUrl(
                  script.url,
                  paramKeys,
                  paramInputs,
                  undefined,
                  optionalParamKeys,
                  optionalParamInputs,
                ),
              }
            : {
                ...script,
                code: formatCode(script.code, paramInputs, optionalParamInputs),
              };
        })
      : undefined,
  };
}
