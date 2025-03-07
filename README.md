 Chort-member-Profiler
Custom post type for managing cohort members with course titles and categories.
To use the Cohorts Memeber Plugin, follow these steps:

 1. Install and Activate the Plugin
- Install: Upload the plugin to your WordPress site by placing the plugin folder into the `/wp-content/plugins/` directory or install it via the WordPress admin interface.
  1. Go to Dashboard > Plugins > Add New.
  2. Click Upload Plugin and upload the `tagdevcohorts` plugin folder (or zip file).
  3. After uploading, click Activate.

 2. Create Cohorts (Custom Post Type)
Once the plugin is activated:
- Create Cohort Posts: Go to Dashboard > TagDev Cohorts and click Add New.
  - Add a title for your cohort (this will be used to auto-fill the `Name` field).
  - Enter the Course Title in the Cohort Course field.
  - Assign categories for the cohort under Cohort Categories (you can create custom categories).
  - Save the cohort post.

 3. Add Cohort Categories
You can create categories for the cohorts, which will be used for filtering. To add categories:
- Go to Dashboard > TagDev Cohorts > Cohort Categories.
- Click Add New Category and give it a name.

 4. Use the Shortcode to Display Cohorts
To display the cohort list on a page or post, use the shortcode `[tagdevcohorts]`.

- Add Shortcode to a Page:
  1. Go to Pages > Add New (or edit an existing page).
  2. Add the following shortcode where you want to display the cohort list:
     ```plaintext
     [tagdevcohorts show_tabs="true"]
     ```
     This will display a list of cohorts with filterable tabs for the cohort categories. You can set `show_tabs` to `true` to display the category tabs or `false` if you don't want the tabs.

  3. Publish or update the page.

- Customizing the Shortcode:
  - If you want to display the cohort list without tabs, use:
    ```plaintext
    [tagdevcohorts show_tabs="false"]
    ```

 5. View Cohorts on the Frontend
Once the shortcode is added to your page, visitors can view all cohorts listed with category tabs (if `show_tabs` is set to `true`).

 6. Cohort Details
Each cohort will display:
- Thumbnail (if available)
- Cohort Name (post title)
- Course Title
- Filtered by Cohort Categories (if enabled)

 7. Additional Features:
- You can modify the layout and appearance by adjusting the styles in the plugin's code under the `tagdevcohorts_display_shortcode` function (inside the `<style>` tags).
- The cohort name is auto-filled based on the post title using the JavaScript provided in the plugin's code.

 Example Page Setup
1. Create a page in WordPress.
2. Add the shortcode to the page content:
   ```plaintext
   [tagdevcohorts show_tabs="true"]
   ```
3. View the page, and you should see the cohorts displayed with filtering options by cohort categories.

 Troubleshooting:
- If the cohorts or categories are not showing correctly, check your permalinks settings. Go to Dashboard > Settings > Permalinks, and click Save Changes to refresh the permalink structure.
- Ensure that the custom post type and taxonomy are correctly registered in your WordPress installation.

With these steps, you should be able to use the TagDevCohorts plugin to manage and display cohort information effectively.
