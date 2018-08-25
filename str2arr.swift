import Foundation
import Glibc
func string2array(strin: String) -> [String:Any]
{
var str = strin
func string2arr(str: inout String) -> [String:Any]
{
    var dict : [String:Any] = [:]
    while(str.contains(":"))
    {
        var miniDict = str.split(separator:":", maxSplits:1)
        str = String(miniDict[1])
        var val = str.split(separator:",", maxSplits:1)
        if(val[0].contains("{"))
        {
            str = String(str[str.index(str.startIndex,offsetBy:1)...])
            dict[String(miniDict[0])] = string2arr(str: &str)
        }
        else if(val[0].contains("}"))
        {
            var l = str.split(separator:"}", maxSplits:1)
            dict[String(miniDict[0])] = String(l[0])
            str = String(l[1])
            return dict
        }
        else
        {
            dict[String(miniDict[0])] = val[0]
            str = String(val[1])
        }
    }
    return dict
}
str = str.replacingOccurrences(of: "\"", with: "", options: NSString.CompareOptions.literal, range:nil)
str = String(str[str.index(str.startIndex,offsetBy:1)..<str.index(str.endIndex,offsetBy:-1)])
let l = string2arr(str:&str)
return l
}
