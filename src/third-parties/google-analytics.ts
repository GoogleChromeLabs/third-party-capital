// Copyright 2023 Google LLC

// Licensed under the Apache License, Version 2.0 (the "License");
// you may not use this file except in compliance with the License.
// You may obtain a copy of the License at

//     https://www.apache.org/licenses/LICENSE-2.0

// Unless required by applicable law or agreed to in writing, software
// distributed under the License is distributed on an "AS IS" BASIS,
// WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
// See the License for the specific language governing permissions and
// limitations under the License.

export const GoogleAnalytics = {
  id: "google-analytics",
  description: "Install a Google Analytics tag on your website",
  website: "https://analytics.google.com/analytics/web/",
  scripts: [
    {
      url: "https://www.googletagmanager.com/gtag/js?id=${args.id}",
      strategy: "worker",
      location: "head",
      action: "append",
    },
    {
      code: "window.dataLayer=window.dataLayer||[];window.gtag=function gtag(){window.dataLayer.push(arguments);};gtag('js',new Date());gtag('config','${args.id}')",
      strategy: "worker",
      location: "head",
      action: "append",
    }
  ],
};
