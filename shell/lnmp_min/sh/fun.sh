color_text()
{
  echo -e " \e[0;$2m$1\e[0m"
}
echo_red()
{
  echo $(color_text "$1" "31")
}
echo_green()
{
  echo $(color_text "$1" "32")
}
echo_yellow()
{
  echo $(color_text "$1" "33")
}
echo_blue()
{
  echo $(color_text "$1" "34")
}