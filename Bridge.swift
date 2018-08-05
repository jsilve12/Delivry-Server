func create_request(url_root: String, url_base: String) -> URLRequest
{
  //Prepares the upload request
  let complete_url = URL(string: url_base + url)
  var request = URLRequest(url:complete_url)
  request.httpMethod = "POST"
  request.setValue(application/json, forHTTPHeaderField: "Content-Type")
  return request
}
Class bridge
{
  var url_base: String
  init(url: String)
  {
    self.url_base = url
  }

  func post(url: String, parameters: Array) ->Array
  {
    var request = create_request(self.url_base, url)

    //Configures the parameters
    guard let upload_data = try? JSONEncoder().encode(parameters) else { return [error: "Bad Array"]}

    //Actual uploading
    let task = URLSession.shared.uploadTask(with:request, from: uploadData) { data, response, error in
    if let error = error{ return ["error": error]}
    //Ensures that it reached the right pages
    guard let response = response as? HTTPURLResponse, (200...299).contains(response.statusCode) else{ return [error: "Server Error"]}
    if let mimeType = response.mimeType,
      mimeType == "application/json",
      let data = data{
      return data
      }
    else
    {
      return data
    }
    task.resume()
    }
    func post_with_image(url: String, image:URL)
    {
      var request = create_request(self.url_base, url)
      //Actually uploads the url
      let task = URLSession.shared.uploadTask(with: request, fromFile: image) {data, response, error in
      if let error = error{return ["error":error]}
      //Ensures that it reached the right pages
      guard let response = response as? HTTPURLResponse, (200...299).contains(response.statusCode) else{ return [error: "Server Error"]}
      if let mimeType = response.mimeType,
        mimeType == "application/json",
        let data = data{
        return data
        }
      else
      {
        return data
      }
      }
    }
  }
}
